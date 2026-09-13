<?php
/**
 * Shared DB connection for the careers application system.
 * Reuses the same MySQL server/credentials already configured for
 * TYPO3 (typo3conf/LocalConfiguration.php) instead of introducing a
 * second database on the hosting account.
 */

function careers_db(): mysqli
{
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }

    $configPath = dirname(__DIR__) . '/typo3conf/LocalConfiguration.php';
    if (!file_exists($configPath)) {
        http_response_code(500);
        die(json_encode(['success' => false, 'error' => 'Database configuration not found.']));
    }

    $typo3Config = require $configPath;
    $db = $typo3Config['DB']['Connections']['Default'] ?? null;

    if (!$db || empty($db['host']) || empty($db['user']) || empty($db['dbname'])) {
        http_response_code(500);
        die(json_encode(['success' => false, 'error' => 'Database configuration is incomplete.']));
    }

    // PHP 8.1+ makes mysqli throw exceptions on connection failure by
    // default. Switch to the old-style "check the property" behaviour so
    // a bad connection shows our own error message instead of a raw 500.
    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = @new mysqli($db['host'], $db['user'], $db['password'], $db['dbname'], $db['port'] ?? 3306);
    if (!$conn || $conn->connect_error) {
        http_response_code(500);
        $detail = $conn ? $conn->connect_error : 'Could not initialize connection.';
        die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $detail]));
    }
    $conn->set_charset('utf8mb4');

    return $conn;
}