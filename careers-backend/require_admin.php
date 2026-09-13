<?php
/**
 * Shared guard for admin-only write endpoints (blog/jobs/gallery save & delete).
 * Include this before doing anything else in those files.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['careers_admin'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'error' => 'Not authorized. Please log in at careers-admin.php.']));
}
