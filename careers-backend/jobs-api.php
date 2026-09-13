<?php
/**
 * Public read-only endpoint — returns active job/internship postings as JSON.
 * Consumed by careers.html (listings) and application.html (position picker).
 */

require __DIR__ . '/db_config.php';
header('Content-Type: application/json');

$conn = careers_db();
$res = $conn->query(
    'SELECT id, title, category, job_type, meta_line, description, requirements, created_at
     FROM codebridge_jobs
     WHERE status = "active"
     ORDER BY created_at DESC'
);

$jobs = [];
while ($row = $res->fetch_assoc()) {
    $jobs[] = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'category' => $row['category'],
        'type' => $row['job_type'],
        'meta' => $row['meta_line'],
        'description' => $row['description'],
        'requirements' => $row['requirements'] ? preg_split('/\r\n|\r|\n/', trim($row['requirements'])) : [],
    ];
}

echo json_encode(['success' => true, 'jobs' => $jobs]);
