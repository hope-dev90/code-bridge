<?php
/** Admin-only: delete a blog post and its image. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id) {
    $conn = careers_db();
    $stmt = $conn->prepare('SELECT image_filename FROM codebridge_blog_posts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && $row['image_filename']) {
        $path = dirname(__DIR__) . '/uploads/blog/' . $row['image_filename'];
        if (is_file($path)) {
            unlink($path);
        }
    }

    $stmt = $conn->prepare('DELETE FROM codebridge_blog_posts WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header('Location: careers-admin.php?tab=blog');
