<?php
/**
 * Public read-only endpoint — returns published gallery photos as JSON.
 * Consumed by gallery.html and the blog/gallery cross-link previews.
 */

require __DIR__ . '/db_config.php';
header('Content-Type: application/json');

$conn = careers_db();
$limit = isset($_GET['limit']) ? max(1, min(60, (int) $_GET['limit'])) : 40;

$stmt = $conn->prepare(
    'SELECT id, title, tag, image_filename, created_at
     FROM codebridge_gallery
     WHERE status = "published"
     ORDER BY created_at DESC
     LIMIT ?'
);
$stmt->bind_param('i', $limit);
$stmt->execute();
$res = $stmt->get_result();

$photos = [];
while ($row = $res->fetch_assoc()) {
    $photos[] = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'tag' => $row['tag'],
        'image' => 'uploads/gallery/' . $row['image_filename'],
    ];
}

echo json_encode(['success' => true, 'photos' => $photos]);
