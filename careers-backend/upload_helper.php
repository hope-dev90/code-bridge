<?php
/**
 * Shared helper for saving publicly-viewable content images (blog/gallery),
 * as opposed to careers-apply.php's private CV uploads.
 */

function save_content_image(string $inputName, string $subfolder): ?string
{
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Image upload failed.']));
    }
    if ($file['size'] > 8 * 1024 * 1024) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Image too large (max 8MB).']));
    }
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => 'Unsupported image type. Use JPG, PNG, WEBP or GIF.']));
    }

    $uploadDir = dirname(__DIR__) . '/uploads/' . $subfolder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $storedName)) {
        http_response_code(500);
        die(json_encode(['success' => false, 'error' => 'Could not save uploaded image.']));
    }
    return $storedName;
}
