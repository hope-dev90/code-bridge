<?php
/**
 * Handles the "Send us a Message" form on contact.html.
 * No database needed here — it just emails the enquiry to the team inbox.
 */

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
    echo json_encode(['success' => true]);
    exit;
}

// Simple "I'm not a robot" checkbox, enforced server-side too.
if (empty($_POST['not_robot'] ?? '')) {
    fail('Please confirm you are not a robot.');
}

$name = trim($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$sector = trim($_POST['sector'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || !$email || $message === '') {
    fail('Please fill in your name, a valid email, and a message.');
}

$to = 'info@codebrige.rw';
$subject = 'New website enquiry from ' . $name;
$body = "Name: {$name}\nEmail: {$email}\nSector of interest: {$sector}\n\nMessage:\n{$message}\n";
$headers = "From: no-reply@codebrige.rw\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8";

$sent = @mail($to, $subject, $body, $headers);
if (!$sent) {
    fail('Could not send your message right now. Please try again later.', 500);
}

echo json_encode(['success' => true]);
