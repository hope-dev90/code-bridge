<?php
/**
 * Public read-only endpoint — returns published Technical Reels as JSON.
 * Consumed by innovations.html's Technical Reels section.
 */

require __DIR__ . '/db_config.php';
header('Content-Type: application/json');

$conn = careers_db();
$limit = isset($_GET['limit']) ? max(1, min(20, (int) $_GET['limit'])) : 12;

$stmt = $conn->prepare(
    'SELECT id, title, tag, youtube_id, image_filename, created_at
     FROM codebridge_reels
     WHERE status = "published"
     ORDER BY created_at DESC
     LIMIT ?'
);
if (!$stmt) {
    // Table doesn't exist yet (no reel uploaded through the admin panel yet).
    echo json_encode(['success' => true, 'reels' => []]);
    exit;
}
$stmt->bind_param('i', $limit);
$stmt->execute();
$res = $stmt->get_result();

$reels = [];
while ($row = $res->fetch_assoc()) {
    $reels[] = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'tag' => $row['tag'],
        'youtubeId' => $row['youtube_id'],
        'image' => 'uploads/reels/' . $row['image_filename'],
    ];
}

echo json_encode(['success' => true, 'reels' => $reels]);
