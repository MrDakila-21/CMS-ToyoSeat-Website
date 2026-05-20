<?php
// app/Models/History.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';
    
    protected $fillable = [
        'title',
        'description',
        'image',
        'date',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = ['image_url'];

    // Helper to get full image URL with cache-busting timestamp
    public function getImageUrlAttribute()
    {
        $timestamp = time(); // Fallback timestamp
        
        // PRIORITY 1: Check for image in public/images/histories folder with ID as filename
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            $historyImagePath = public_path("images/histories/{$this->id}.{$ext}");
            if (file_exists($historyImagePath)) {
                $timestamp = filemtime($historyImagePath);
                return "/storage.php?file=images/histories/{$this->id}.{$ext}&t={$timestamp}";
            }
        }
        
        // PRIORITY 2: Check if there's a stored image path in database (from upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            $timestamp = Storage::disk('public')->lastModified($this->image);
            return '/storage.php?file=' . urlencode($this->image) . '&t=' . $timestamp;
        }
        
        // PRIORITY 3: Return default image if no image found
        $defaultImagePath = public_path('storage/app/public/images/default-image.png');
        if (file_exists($defaultImagePath)) {
            $timestamp = filemtime($defaultImagePath);
        }
        
        return '/storage.php?file=images/default-image.png&t=' . $timestamp;
    }
    
    // Method to check if folder image exists
    public function hasFolderImage()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            $historyImagePath = public_path("images/histories/{$this->id}.{$ext}");
            if (file_exists($historyImagePath)) {
                return true;
            }
        }
        
        return false;
    }
    
    // Method to sync image from folder by ID
    public function syncImageFromFolder()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            $historyImagePath = public_path("images/histories/{$this->id}.{$ext}");
            if (file_exists($historyImagePath)) {
                // If folder image exists, clear database image path to prioritize folder
                if ($this->image) {
                    $this->image = null;
                    $this->save();
                }
                return true;
            }
        }
        
        return false;
    }
    
    // Method to delete folder image if exists
    public function deleteFolderImage()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        $deleted = false;
        
        foreach ($imageExtensions as $ext) {
            $historyImagePath = public_path("images/histories/{$this->id}.{$ext}");
            if (file_exists($historyImagePath)) {
                unlink($historyImagePath);
                $deleted = true;
            }
        }
        
        return $deleted;
    }
    
    // Get the folder image path if exists
    public function getFolderImagePath()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            $historyImagePath = public_path("images/histories/{$this->id}.{$ext}");
            if (file_exists($historyImagePath)) {
                return $historyImagePath;
            }
        }
        
        return null;
    }
    
    // Static method to sync all images
    public static function syncAllImagesFromFolder()
    {
        $items = self::all();
        $updated = 0;
        
        foreach ($items as $item) {
            if ($item->syncImageFromFolder()) {
                $updated++;
            }
        }
        
        return $updated;
    }
    
    // Save image directly to public folder
    public function saveImageToPublicFolder($imageFile)
    {
        // Delete any existing folder image first
        $this->deleteFolderImage();
        
        // Delete old database image if exists
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
        
        $extension = $imageFile->getClientOriginalExtension();
        $filename = "{$this->id}.{$extension}";
        
        // Create histories directory if it doesn't exist
        $historyPath = public_path('images/histories');
        if (!file_exists($historyPath)) {
            mkdir($historyPath, 0755, true);
        }
        
        // Move the uploaded file
        $imageFile->move($historyPath, $filename);
        
        // Clear the database image path (so it uses the folder image)
        $this->image = null;
        $this->save();
        
        return true;
    }
    
    // Delete all images (both storage and public folder)
    public function deleteAllImages()
    {
        // Delete from storage
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
        
        // Delete from public folder
        $this->deleteFolderImage();
    }
    
    // Override delete to remove images
    protected static function booted()
    {
        static::deleting(function ($history) {
            $history->deleteAllImages();
        });
    }
}