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

// Define default image path
$defaultImagePath = __DIR__ . '/images/default-image.png';
$storageDefaultPath = __DIR__ . '/storage/app/public/images/default-image.png';

// Check if the requested file is empty or null
if (empty($file)) {
    // Serve default image if available
    if (file_exists($defaultImagePath)) {
        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        readfile($defaultImagePath);
        exit;
    } elseif (file_exists($storageDefaultPath)) {
        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        readfile($storageDefaultPath);
        exit;
    }
    http_response_code(404);
    echo 'No image specified and default image not found';
    exit;
}

// Build the full path to the file
$storagePath = __DIR__ . '/storage/app/public/' . $file;

// Check if file exists and is a file
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
}

// File not found - Serve default image from public/images/ or storage
if (file_exists($defaultImagePath)) {
    header('Content-Type: image/png');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    readfile($defaultImagePath);
    exit;
} elseif (file_exists($storageDefaultPath)) {
    header('Content-Type: image/png');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    readfile($storageDefaultPath);
    exit;
}

// No default image found, return 404
http_response_code(404);
echo 'File not found: ' . htmlspecialchars($file);
?>