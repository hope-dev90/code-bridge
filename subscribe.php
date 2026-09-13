<?php
/**
 * Newsletter signup — public POST endpoint.
 * Saves the subscriber first (source of truth for the mailing list), then
 * best-effort sends a welcome email via PHPMailer/SMTP.
 * SMTP credentials stay in /home/codebrig/.env (never in this file — never in frontend JS).
 */

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require __DIR__ . '/cb-data.php'; // for cb_db()

function loadEnv($path) {
    if (!is_file($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if ($key === '') {
            continue;
        }
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}
loadEnv('/home/codebrig/.env');

$data = json_decode(file_get_contents('php://input'), true);
$subscriberEmail = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$subscriberEmail || strlen($subscriberEmail) > 190) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

// Persist the subscriber first — this is the actual mailing list, and it
// must not be lost just because the welcome email fails to send.
$conn = cb_db();
$isNewSubscriber = true;
if ($conn) {
    $conn->query(
        'CREATE TABLE IF NOT EXISTS codebridge_newsletter (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(190) NOT NULL UNIQUE,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );
    $stmt = $conn->prepare('INSERT IGNORE INTO codebridge_newsletter (email) VALUES (?)');
    $stmt->bind_param('s', $subscriberEmail);
    $stmt->execute();
    $isNewSubscriber = $stmt->affected_rows > 0;
} else {
    error_log('subscribe.php: no DB connection, subscriber list not updated for ' . $subscriberEmail);
}

if (!$isNewSubscriber) {
    echo json_encode(['success' => true, 'message' => "You're already subscribed — thanks!"]);
    exit;
}

// Path pointing to Composer's autoloader in public_html/careers-backend/vendor/
$autoload = __DIR__ . '/careers-backend/vendor/autoload.php';
if (!is_file($autoload)) {
    error_log('subscribe.php: PHPMailer autoloader missing at ' . $autoload . ' — subscriber saved, welcome email skipped');
    echo json_encode(['success' => true, 'message' => 'Subscribed! Thanks for joining.']);
    exit;
}
require $autoload;

$smtpPass = getenv('SMTP_PASS');
if (!$smtpPass) {
    error_log('subscribe.php: SMTP_PASS not set in /home/codebrig/.env — subscriber saved, welcome email skipped');
    echo json_encode(['success' => true, 'message' => 'Subscribed! Thanks for joining.']);
    exit;
}
$smtpHost = getenv('SMTP_HOST') ?: 'mail.codebrige.rw';
$smtpUser = getenv('SMTP_USER') ?: 'info@codebrige.rw';

$mail = new PHPMailer(true);

try {
    // Server SMTP configuration
    $mail->isSMTP();
    $mail->Host       = $smtpHost;                    // Host domain
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;                     // Webmail / SMTP username
    $mail->Password   = $smtpPass;                      // Loaded from /home/codebrig/.env — never hardcoded here
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL Port 465
    $mail->Port       = 465;

    // Sender and Recipient
    $mail->setFrom($smtpUser, 'Codebridge');
    $mail->addAddress($subscriberEmail);

    // Email Body Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Codebridge Newsletter!';
    $mail->Body    = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
          <h2>Thanks for subscribing!</h2>
          <p>You have successfully joined the Codebridge newsletter. We will keep you updated on our latest projects and news.</p>
          <br>
          <p>Best regards,<br><strong>Codebridge Team</strong></p>
        </body>
        </html>
    ";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Subscription successful! Check your inbox.']);
} catch (Exception $e) {
    // Subscriber is already saved above, so this is still a real subscription —
    // just log the mail failure server-side instead of exposing SMTP details to the client.
    error_log('subscribe.php: welcome email failed for ' . $subscriberEmail . ' — ' . $mail->ErrorInfo);
    echo json_encode(['success' => true, 'message' => "Subscribed! We couldn't send the welcome email, but you're on the list."]);
}
