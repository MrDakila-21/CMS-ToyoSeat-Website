<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IsoObtained extends Model
{
    use HasFactory;

    protected $table = 'iso_obtained';

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'is_active',
        'is_intro',
    ];

    protected $casts = [
        'status' => 'string',
        'is_active' => 'boolean',
        'is_intro' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Helper to get full image URL
    public function getImageUrlAttribute()
    {
        // PRIORITY 1: Check for image in public/images/iso-obtained folder (direct upload)
        $imageExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        foreach ($imageExtensions as $ext) {
            $directImagePath = public_path("images/iso-obtained/{$this->id}.{$ext}");
            if (file_exists($directImagePath)) {
                return route('image.serve', ['path' => "images/iso-obtained/{$this->id}.{$ext}"]);
            }
        }

        // PRIORITY 2: Check if there's a stored image path in database (normal upload)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return route('image.serve', ['path' => $this->image]);
        }

        // PRIORITY 3: Return default image if no image found
        return route('image.serve', ['path' => 'images/default-image.png']);
    }

    // Normal upload - save to storage (Laravel storage system)
    public function saveImage($file)
    {
        // Delete old image from storage if exists
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }

        // Store the image in storage/app/public/iso-obtained
        $path = $file->store('iso-obtained', 'public');
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
        $isoPath = public_path('images/iso-obtained');
        if (! file_exists($isoPath)) {
            mkdir($isoPath, 0755, true);
        }

        // Delete old image from public folder if exists
        $this->deletePublicFolderImages();

        // Move the uploaded file directly to public folder
        $file->move($isoPath, $filename);

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
            $imagePath = public_path("images/iso-obtained/{$this->id}.{$ext}");
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
    }

    // Delete both storage and public folder images
    public function deleteAllImages()
    {
        // Delete from storage if exists
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }

        // Delete from public folder
        $this->deletePublicFolderImages();
    }
}
