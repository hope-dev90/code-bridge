<?php
/** Admin-only: delete a job/internship posting. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id) {
    $conn = careers_db();
    $stmt = $conn->prepare('DELETE FROM codebridge_jobs WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header('Location: careers-admin.php?tab=jobs');
