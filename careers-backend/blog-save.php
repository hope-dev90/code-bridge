<?php
/** Admin-only: create or update a blog post. Classic form POST + redirect. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';
require __DIR__ . '/upload_helper.php';

function back(string $error = ''): void
{
    $url = 'careers-admin.php?tab=blog';
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

$title = trim($_POST['title'] ?? '');
$excerpt = trim($_POST['excerpt'] ?? '');
$body = trim($_POST['body'] ?? '');
$badge = trim($_POST['badge'] ?? '') ?: null;
$status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';
$id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;

if ($title === '' || $excerpt === '') {
    back('Title and excerpt are required.');
}

$imageFilename = save_content_image('image', 'blog');
$conn = careers_db();

if ($id) {
    if ($imageFilename) {
        $stmt = $conn->prepare('UPDATE codebridge_blog_posts SET title=?, excerpt=?, body=?, badge=?, status=?, image_filename=? WHERE id=?');
        $stmt->bind_param('ssssssi', $title, $excerpt, $body, $badge, $status, $imageFilename, $id);
    } else {
        $stmt = $conn->prepare('UPDATE codebridge_blog_posts SET title=?, excerpt=?, body=?, badge=?, status=? WHERE id=?');
        $stmt->bind_param('sssssi', $title, $excerpt, $body, $badge, $status, $id);
    }
} else {
    $stmt = $conn->prepare('INSERT INTO codebridge_blog_posts (title, excerpt, body, badge, status, image_filename) VALUES (?,?,?,?,?,?)');
    $stmt->bind_param('ssssss', $title, $excerpt, $body, $badge, $status, $imageFilename);
}

if (!$stmt->execute()) {
    back('Could not save the post.');
}

back();
