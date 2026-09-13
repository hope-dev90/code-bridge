<?php
/**
 * Handles job application submissions from application.html.
 *
 * CHANGED: applications are no longer stored in MySQL. Instead, this emails
 * the full application (with CV / cover letter as real attachments) to
 * APPLICATIONS_TO_EMAIL, defined in mail_config.php. Uploaded files are
 * written to a temp folder just long enough to attach them, then deleted.
 */

require __DIR__ . '/mail_config.php';

// PHPMailer — see install instructions at the bottom of this file.
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

function fail(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail('Method not allowed.', 405);
}

// Honeypot — real visitors never fill this hidden field in.
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true]); // pretend success, drop silently
    exit;
}

$required = ['position', 'full_name', 'email', 'phone'];
foreach ($required as $field) {
    if (trim($_POST[$field] ?? '') === '') {
        fail("Missing required field: {$field}");
    }
}

$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
if (!$email) {
    fail('Please provide a valid email address.');
}

function str_field(string $key, int $maxLen = 255): string
{
    $val = trim($_POST[$key] ?? '');
    return mb_substr($val, 0, $maxLen);
}

// ---- Collect the plain-text fields ----
$position      = str_field('position', 120);
$fullName      = str_field('full_name', 150);
$phone         = str_field('phone', 40);
$company       = str_field('company', 150);
$dob           = str_field('date_of_birth', 10);
$location      = str_field('location', 150);
$linkedin      = str_field('linkedin_url', 255);
$institution1  = str_field('institution1', 190);
$degree1       = str_field('degree1', 100);
$field1        = str_field('field_of_study1', 150);
$gradYear1     = str_field('graduation_year1', 4);
$institution2  = str_field('institution2', 190);
$degree2       = str_field('degree2', 100);
$skills        = str_field('skills', 400);
$note          = str_field('note', 4000);

// ---- Handle uploads: save to a temp path just long enough to attach ----
$allowedExt = ['pdf', 'doc', 'docx'];
$maxBytes   = 5 * 1024 * 1024; // 5MB
$tempFiles  = []; // paths to unlink() once the email is sent

function stage_upload(string $inputName, array $allowedExt, int $maxBytes, array &$tempFiles): ?array
{
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        fail('File upload failed for ' . $inputName . '.');
    }
    if ($file['size'] > $maxBytes) {
        fail('File too large (max 5MB): ' . $inputName);
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        fail('Unsupported file type for ' . $inputName . '. Use PDF, DOC or DOCX.');
    }

    $tempDir = sys_get_temp_dir();
    $tempName = $tempDir . '/' . bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $tempName)) {
        fail('Could not process uploaded file: ' . $inputName);
    }
    $tempFiles[] = $tempName;

    // Keep the applicant's original filename for the email attachment label.
    $safeOriginalName = preg_replace('/[^a-zA-Z0-9._ -]/', '', $file['name']);
    return ['path' => $tempName, 'name' => $safeOriginalName ?: ('file.' . $ext)];
}

$cv          = stage_upload('cv', $allowedExt, $maxBytes, $tempFiles);
$coverLetter = stage_upload('cover_letter', $allowedExt, $maxBytes, $tempFiles);

// ---- Build the email ----
function cleanup(array $tempFiles): void
{
    foreach ($tempFiles as $path) {
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

function esc(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

$rows = [
    'Position'          => $position,
    'Full name'         => $fullName,
    'Email'             => $email,
    'Phone'             => $phone,
    'Company'           => $company,
    'Date of birth'     => $dob,
    'Location'          => $location,
    'LinkedIn/Portfolio'=> $linkedin,
    'Institution 1'     => $institution1,
    'Degree 1'          => $degree1,
    'Field of study 1'  => $field1,
    'Graduation year 1' => $gradYear1,
    'Institution 2'     => $institution2,
    'Degree 2'          => $degree2,
    'Skills'            => $skills,
    'Note'              => $note,
];

$htmlRows = '';
foreach ($rows as $label => $value) {
    if ($value === '') continue;
    $htmlRows .= '<tr><td style="padding:6px 12px;color:#666;font-weight:bold;white-space:nowrap;vertical-align:top;">'
        . esc($label) . '</td><td style="padding:6px 12px;">' . nl2br(esc($value)) . '</td></tr>';
}

$html = '<div style="font-family:sans-serif;max-width:640px;">'
    . '<h2 style="margin:0 0 4px;">New job application</h2>'
    . '<p style="color:#666;margin:0 0 18px;">' . esc($position) . '</p>'
    . '<table style="border-collapse:collapse;width:100%;">' . $htmlRows . '</table>'
    . '<p style="color:#999;font-size:12px;margin-top:20px;">Submitted from the Codebridge site.</p>'
    . '</div>';

$plain = '';
foreach ($rows as $label => $value) {
    if ($value === '') continue;
    $plain .= $label . ": " . $value . "\n";
}

$mail = new PHPMailer(true);

try {
    if (SMTP_ENABLED) {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
    }

    $mail->setFrom(APPLICATIONS_FROM_EMAIL, APPLICATIONS_FROM_NAME);
    $mail->addAddress(APPLICATIONS_TO_EMAIL, APPLICATIONS_TO_NAME);
    // Lets the owner hit "Reply" and email the applicant directly.
    $mail->addReplyTo($email, $fullName);

    $mail->Subject = 'New application: ' . $position . ' — ' . $fullName;
    $mail->isHTML(true);
    $mail->Body    = $html;
    $mail->AltBody = $plain;

    if ($cv) {
        $mail->addAttachment($cv['path'], 'CV - ' . $fullName . ' - ' . $cv['name']);
    }
    if ($coverLetter) {
        $mail->addAttachment($coverLetter['path'], 'Cover Letter - ' . $fullName . ' - ' . $coverLetter['name']);
    }

    $mail->send();

    // ---- Second email: thank-you confirmation sent to the applicant ----
    try {
        $confirm = new PHPMailer(true);
        if (SMTP_ENABLED) {
            $confirm->isSMTP();
            $confirm->Host       = SMTP_HOST;
            $confirm->SMTPAuth   = true;
            $confirm->Username   = SMTP_USERNAME;
            $confirm->Password   = SMTP_PASSWORD;
            $confirm->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $confirm->Port       = SMTP_PORT;
        }
        $confirm->setFrom(APPLICATIONS_FROM_EMAIL, APPLICATIONS_FROM_NAME);
        $confirm->addAddress($email, $fullName);
        $confirm->Subject = 'We received your application  Codebridge';
        $confirm->isHTML(true);
        $confirm->Body = '<div style="font-family:sans-serif;max-width:560px;">'
            . '<h2 style="margin:0 0 12px;">Thanks for applying, ' . esc($fullName) . '!</h2>'
            . '<p style="color:#333;line-height:1.6;">We\'ve received your application for the '
            . '<strong>' . esc($position) . '</strong> position at Codebridge. Our team will review it '
            . 'and reach out within 5–7 business days if there\'s a fit.</p>'
            . '<p style="color:#999;font-size:12px;margin-top:24px;">This is an automated confirmation — '
            . 'no need to reply, but if you have questions you can reach us at ' . esc(APPLICATIONS_FROM_EMAIL) . '.</p>'
            . '</div>';
        $confirm->AltBody = "Thanks for applying, {$fullName}!\n\nWe've received your application for the {$position} position at Codebridge. Our team will review it and reach out within 5-7 business days if there's a fit.";
        $confirm->send();
    } catch (Exception $e) {
        // Don't fail the whole submission if only the confirmation email
        // fails to send — the application itself already went through.
        error_log('Applicant confirmation email failed: ' . $confirm->ErrorInfo);
    }

    cleanup($tempFiles);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    cleanup($tempFiles);
    error_log('Application email failed: ' . $mail->ErrorInfo);
    fail('Could not send your application. Please try again or email us directly.', 500);
}

/* ============================================================
   INSTALL PHPMAILER (one-time setup)
   ============================================================
   This file needs the PHPMailer library at:
     careers-backend/vendor/autoload.php

   Easiest option on cPanel — no command line needed:
   1. Download PHPMailer manually:
      https://github.com/PHPMailer/PHPMailer/releases
      Grab the "Source code (zip)" of the latest release.
   2. Unzip it. You need three files from its src/ folder:
        PHPMailer.php, SMTP.php, Exception.php
   3. In cPanel File Manager, create this folder structure inside
      careers-backend/:
        vendor/phpmailer/phpmailer/src/PHPMailer.php
        vendor/phpmailer/phpmailer/src/SMTP.php
        vendor/phpmailer/phpmailer/src/Exception.php
   4. Create careers-backend/vendor/autoload.php with this content:

        <?php
        require __DIR__ . '/phpmailer/phpmailer/src/Exception.php';
        require __DIR__ . '/phpmailer/phpmailer/src/PHPMailer.php';
        require __DIR__ . '/phpmailer/phpmailer/src/SMTP.php';

   If your host gives you SSH/terminal access instead, this is much
   faster:
        cd careers-backend
        composer require phpmailer/phpmailer
   (composer creates the vendor/ folder and autoload.php for you).

   Then open mail_config.php in this same folder and fill in your real
   email address and SMTP mailbox credentials.
   ============================================================ */