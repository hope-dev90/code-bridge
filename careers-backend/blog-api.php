<?php
/**
 * Public read-only endpoint — returns published blog posts as JSON.
 * Consumed by blog.html and index.html's blog carousel.
 */

require __DIR__ . '/db_config.php';
header('Content-Type: application/json');

$conn = careers_db();
$limit = isset($_GET['limit']) ? max(1, min(50, (int) $_GET['limit'])) : 20;

$stmt = $conn->prepare(
    'SELECT id, title, excerpt, image_filename, badge, created_at
     FROM codebridge_blog_posts
     WHERE status = "published"
     ORDER BY created_at DESC
     LIMIT ?'
);
$stmt->bind_param('i', $limit);
$stmt->execute();
$res = $stmt->get_result();

$posts = [];
while ($row = $res->fetch_assoc()) {
    $posts[] = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'excerpt' => $row['excerpt'],
        'image' => $row['image_filename'] ? 'uploads/blog/' . $row['image_filename'] : null,
        'badge' => $row['badge'],
        'date' => date('l, F j Y', strtotime($row['created_at'])),
    ];
}

echo json_encode(['success' => true, 'posts' => $posts]);
