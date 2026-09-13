<?php
/** Admin-only: upload a new gallery photo. Classic form POST + redirect. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';
require __DIR__ . '/upload_helper.php';

function back(string $error = ''): void
{
    $url = 'careers-admin.php?tab=gallery';
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

$title = trim($_POST['title'] ?? '');
$tag = trim($_POST['tag'] ?? '') ?: null;
$status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

if ($title === '') {
    back('Photo title is required.');
}

$imageFilename = save_content_image('image', 'gallery');
if (!$imageFilename) {
    back('Please choose an image to upload.');
}

$conn = careers_db();
$stmt = $conn->prepare('INSERT INTO codebridge_gallery (title, tag, image_filename, status) VALUES (?,?,?,?)');
$stmt->bind_param('ssss', $title, $tag, $imageFilename, $status);

if (!$stmt->execute()) {
    back('Could not save the photo.');
}

back();
