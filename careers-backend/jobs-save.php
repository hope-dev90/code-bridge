<?php
/** Admin-only: create or update a job/internship posting. Classic form POST + redirect. */

require __DIR__ . '/require_admin.php';
require __DIR__ . '/db_config.php';

function back(string $error = ''): void
{
    $url = 'careers-admin.php?tab=jobs';
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

$title = trim($_POST['title'] ?? '');
$category = in_array($_POST['category'] ?? '', ['dev', 'uiux', 'aiml'], true) ? $_POST['category'] : 'dev';
$jobType = ($_POST['job_type'] ?? 'job') === 'internship' ? 'internship' : 'job';
$meta = trim($_POST['meta_line'] ?? '');
$description = trim($_POST['description'] ?? '');
$requirements = trim($_POST['requirements'] ?? '');
$status = ($_POST['status'] ?? 'active') === 'closed' ? 'closed' : 'active';
$id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;

if ($title === '') {
    back('Job title is required.');
}

$conn = careers_db();

if ($id) {
    $stmt = $conn->prepare('UPDATE codebridge_jobs SET title=?, category=?, job_type=?, meta_line=?, description=?, requirements=?, status=? WHERE id=?');
    $stmt->bind_param('sssssssi', $title, $category, $jobType, $meta, $description, $requirements, $status, $id);
} else {
    $stmt = $conn->prepare('INSERT INTO codebridge_jobs (title, category, job_type, meta_line, description, requirements, status) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('sssssss', $title, $category, $jobType, $meta, $description, $requirements, $status);
}

if (!$stmt->execute()) {
    back('Could not save the job posting.');
}

back();
