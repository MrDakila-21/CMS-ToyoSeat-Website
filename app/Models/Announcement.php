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

    // Helper to get full image URL - HIGHEST PRIORITY: public/images/announcements/{id}.{ext}
    public function getImageUrlAttribute()
    {
        // PRIORITY 1: Check for image in public/images/announcements folder (direct upload)
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        foreach ($imageExtensions as $ext) {
            $directImagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($directImagePath)) {
                return asset("images/announcements/{$this->id}.{$ext}");
            }
        }
        
        // PRIORITY 2: Check if there's a stored image path in database (normal upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        
        // PRIORITY 3: Return default image if no image found
        return asset('images/default-image.png');
    }
    
    // Normal upload - save to storage (Laravel storage system)
    public function saveImage($file)
    {
        // Delete old image from storage if exists
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
        
        // Store the image in storage/app/public/announcements
        $path = $file->store('announcements', 'public');
        $this->image = $path;
        $this->save();
        
        return $path;
    }
    
    // Direct/Folder upload - save directly to public folder with ID as filename
    public function saveImageToPublicFolder($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "{$this->id}.{$extension}";
        
        // Create directory if it doesn't exist
        $announcementsPath = public_path('images/announcements');
        if (!file_exists($announcementsPath)) {
            mkdir($announcementsPath, 0755, true);
        }
        
        // Delete old image from public folder if exists
        $this->deletePublicFolderImages();
        
        // Move the uploaded file directly to public folder
        $file->move($announcementsPath, $filename);
        
        // Clear database image path since we're using public folder (priority 1)
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
            $this->image = null;
            $this->save();
        }
        
        return true;
    }
    
    // Delete images from public folder only
    public function deletePublicFolderImages()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        foreach ($imageExtensions as $ext) {
            $imagePath = public_path("images/announcements/{$this->id}.{$ext}");
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
    
    // Delete all images (both storage and public folder)
    public function deleteAllImages()
    {
        // Delete from storage
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
        
        // Delete from public folder
        $this->deletePublicFolderImages();
    }
    
    // Check if image exists in public folder
    public function hasPublicFolderImage()
    {
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        foreach ($imageExtensions as $ext) {
            if (file_exists(public_path("images/announcements/{$this->id}.{$ext}"))) {
                return true;
            }
        }
        return false;
    }
    
    // Override delete to remove images
    protected static function booted()
    {
        static::deleting(function ($announcement) {
            $announcement->deleteAllImages();
        });
    }
}