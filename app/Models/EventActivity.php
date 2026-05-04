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

    // Helper to get full image URL - Modified to check folder by ID
    public function getImageUrlAttribute()
    {
        // Priority 1: Check if there's a stored image path in database (from upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        
        // Priority 2: Check for image in public/images with ID as filename
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            // Check in public/images directory
            $directImagePath = public_path("images/{$this->id}.{$ext}");
            if (file_exists($directImagePath)) {
                return asset("images/{$this->id}.{$ext}");
            }
            
            // Also check in public/images/events-activities directory if needed
            $folderImagePath = public_path("images/events-activities/{$this->id}.{$ext}");
            if (file_exists($folderImagePath)) {
                return asset("images/events-activities/{$this->id}.{$ext}");
            }
        }
        
        // Priority 3: Return default image if no image found
        return asset('images/default-image.png');
    }
    
    // Method to sync image from folder by ID
    public function syncImageFromFolder()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        
        foreach ($imageExtensions as $ext) {
            // Check in public/images directory
            $directImagePath = public_path("images/{$this->id}.{$ext}");
            if (file_exists($directImagePath)) {
                // If found, optionally move to storage or just use it
                // The getImageUrlAttribute will handle displaying it
                return true;
            }
            
            // Check in public/images/events-activities directory
            $folderImagePath = public_path("images/events-activities/{$this->id}.{$ext}");
            if (file_exists($folderImagePath)) {
                return true;
            }
        }
        
        return false;
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