<?php
// storage.php - Serves images from storage/app/public/
// Place this file in /home/toyoseat/public_html/

// Get the requested file path
$file = $_GET['file'] ?? '';

// Remove cache-busting parameter from consideration
$file = preg_replace('/\?.*$/', '', $file); // Remove query parameters

// Security: Prevent directory traversal attacks
$file = str_replace(['..', './', '\\', "\0"], '', $file);

// Remove any leading slashes
$file = ltrim($file, '/');

// Build the full path to the file
$storagePath = __DIR__ . '/storage/app/public/' . $file;

// Check if file exists
if (file_exists($storagePath) && is_file($storagePath)) {
    // Get file extension
    $ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
    
    // Set appropriate content type
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'css' => 'text/css',
        'js' => 'application/javascript',
    ];
    
    $contentType = $mimeTypes[$ext] ?? mime_content_type($storagePath);
    
    // Get file modification time for cache-busting
    $lastModified = filemtime($storagePath);
    
    // Set headers with aggressive no-cache for images
    header('Content-Type: ' . $contentType);
    header('Content-Length: ' . filesize($storagePath));
    header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
    
    // Output the file
    readfile($storagePath);
    exit;
} else {
    // File not found - Return default image or 404
    $defaultImage = __DIR__ . '/storage/app/public/images/default-image.png';
    if (file_exists($defaultImage)) {
        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        readfile($defaultImage);
        exit;
    }
    
    // No default image, return 404
    http_response_code(404);
    echo 'File not found: ' . htmlspecialchars($file);
}
?>