<?php
/** Admin-only: upload a new Technical Reel. Classic form POST + redirect. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';
require __DIR__ . '/upload_helper.php';

function back(string $error = ''): void
{
    $url = 'careers-admin.php?tab=reels';
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

/** Accepts a full YouTube URL (watch/shorts/youtu.be) or a bare video ID. */
function extract_youtube_id(string $input): ?string
{
    $input = trim($input);
    if (preg_match('/^[A-Za-z0-9_-]{11}$/', $input)) {
        return $input;
    }
    if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?v=|shorts/|embed/))([A-Za-z0-9_-]{11})#', $input, $m)) {
        return $m[1];
    }
    return null;
}

$title = trim($_POST['title'] ?? '');
$tag = trim($_POST['tag'] ?? '') ?: null;
$status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';
$youtubeId = extract_youtube_id($_POST['youtube_url'] ?? '');

if ($title === '') {
    back('Reel title is required.');
}
if (!$youtubeId) {
    back('Please paste a valid YouTube link (watch, shorts, or youtu.be) or an 11-character video ID.');
}

$imageFilename = save_content_image('image', 'reels');
if (!$imageFilename) {
    back('Please choose a poster image to upload.');
}

$conn = careers_db();
$conn->query(
    'CREATE TABLE IF NOT EXISTS codebridge_reels (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(150) NOT NULL,
        tag VARCHAR(80) NULL,
        youtube_id VARCHAR(20) NOT NULL,
        image_filename VARCHAR(255) NOT NULL,
        status ENUM("draft","published") NOT NULL DEFAULT "published",
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_status_created (status, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);

$stmt = $conn->prepare('INSERT INTO codebridge_reels (title, tag, youtube_id, image_filename, status) VALUES (?,?,?,?,?)');
$stmt->bind_param('sssss', $title, $tag, $youtubeId, $imageFilename, $status);

if (!$stmt->execute()) {
    back('Could not save the reel.');
}

back();
