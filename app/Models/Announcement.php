<?php
// app/Models/Announcement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    
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

    // Helper to get full image URL - FIXED for storage.php
    public function getImageUrlAttribute()
    {
        // PRIORITY 1: Check for image in public/images/announcements folder with ID as filename
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            // Check in public/images/announcements directory
            $announcementImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($announcementImagePath)) {
                return "/storage.php?file=images/announcements/{$this->id}.{$ext}";
            }
        }
        
        // PRIORITY 2: Check if there's a stored image path in database (from upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return '/storage.php?file=' . $this->image;
        }
        
        // PRIORITY 3: Return default image if no image found
        return '/storage.php?file=images/default-image.png';
    }
    
    // Method to check if folder image exists
    public function hasFolderImage()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            $announcementImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($announcementImagePath)) {
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
            // Check in public/images/announcements directory
            $announcementImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($announcementImagePath)) {
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
            $announcementImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($announcementImagePath)) {
                unlink($announcementImagePath);
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
            $announcementImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($announcementImagePath)) {
                return $announcementImagePath;
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
        static::deleting(function ($announcement) {
            $announcement->deleteAllImages();
        });
    }
}