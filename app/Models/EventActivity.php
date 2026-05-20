<?php
// app/Models/EventActivity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventActivity extends Model
{
    use HasFactory;

    protected $table = 'events_activities';
    
    protected $fillable = [
        'title',
        'description',
        'image',
        'event_date',
        'status',
        'type'
    ];

    protected $casts = [
        'event_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Helper to get full image URL with cache-busting timestamp
    public function getImageUrlAttribute()
    {
        $timestamp = time(); // Fallback timestamp
        
        // PRIORITY 1: Check for image in public/images/EventActivity folder with ID as filename
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            // Check in public/events-activities directory
            $eventActivityImagePath = public_path("events-activities/{$this->id}.{$ext}");
            if (file_exists($eventActivityImagePath)) {
                // Get file modification time for cache-busting
                $timestamp = filemtime($eventActivityImagePath);
                return "/storage.php?file=events-activities/{$this->id}.{$ext}&t={$timestamp}";
            }
        }
        
        // PRIORITY 2: Check if there's a stored image path in database (from upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            // Get file modification time
            $timestamp = Storage::disk('public')->lastModified($this->image);
            return '/storage.php?file=' . $this->image . '&t=' . $timestamp;
        }
        
        // PRIORITY 3: Return default image with timestamp
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
            $eventActivityImagePath = public_path("events-activities/{$this->id}.{$ext}");
            if (file_exists($eventActivityImagePath)) {
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
            // Check in public/events-activities directory
            $eventActivityImagePath = public_path("events-activities/{$this->id}.{$ext}");
            if (file_exists($eventActivityImagePath)) {
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
            $eventActivityImagePath = public_path("events-activities/{$this->id}.{$ext}");
            if (file_exists($eventActivityImagePath)) {
                unlink($eventActivityImagePath);
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
            $eventActivityImagePath = public_path("events-activities/{$this->id}.{$ext}");
            if (file_exists($eventActivityImagePath)) {
                return $eventActivityImagePath;
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
}