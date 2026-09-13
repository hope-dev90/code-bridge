<?php
/** Admin-only: delete a gallery photo. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id) {
    $conn = careers_db();
    $stmt = $conn->prepare('SELECT image_filename FROM codebridge_gallery WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && $row['image_filename']) {
        $path = dirname(__DIR__) . '/uploads/gallery/' . $row['image_filename'];
        if (is_file($path)) {
            unlink($path);
        }
    }

    $stmt = $conn->prepare('DELETE FROM codebridge_gallery WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header('Location: careers-admin.php?tab=gallery');
