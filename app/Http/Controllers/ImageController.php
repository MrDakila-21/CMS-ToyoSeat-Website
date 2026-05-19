<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function serve($path = '')
    {
        if (! $path) {
            abort(404);
        }

        // Security: Remove directory traversal attempts
        $path = str_replace(['..', './', '\\', "\0"], '', $path);
        $path = ltrim($path, '/');

        // PRIORITY 1: Check in public/images/ (direct public folder images)
        $publicPath = public_path('images/'.$path);
        if (file_exists($publicPath) && is_file($publicPath)) {
            return $this->streamFile($publicPath);
        }

        // PRIORITY 2: Check in storage/app/public/ (Laravel storage)
        if (Storage::disk('public')->exists($path)) {
            return $this->streamStorageFile($path);
        }

        // PRIORITY 3: Return default image
        $defaultPath = public_path('storage/images/default-image.png');
        if (file_exists($defaultPath)) {
            return $this->streamFile($defaultPath);
        }

        abort(404, 'Image not found');
    }

    private function streamFile($filePath)
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
        ];

        $contentType = $mimeTypes[$ext] ?? mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function streamStorageFile($path)
    {
        $fullPath = Storage::disk('public')->path($path);

        return $this->streamFile($fullPath);
    }
}
