<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessContent extends Model
{
    protected $table = 'business_contents';
    
    protected $fillable = [
        'section', 'title', 'subtitle', 'description', 'image', 
        'original_filename', 'name', 'position', 'order', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    // Helper methods to get content by section
    public static function getAutomotiveSeats()
    {
        return self::where('section', 'automotive')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    public static function getOrganizationMembers()
    {
        return self::where('section', 'organization')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    public static function getCharacteristics()
    {
        return self::where('section', 'characteristic')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    public static function getPartnerships()
    {
        return self::where('section', 'partnership')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    /**
     * Get the image URL using storage.php (same pattern as Overview module)
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if file exists to add cache busting
            $path = storage_path('app/public/' . $this->image);
            $mtime = file_exists($path) ? filemtime($path) : time();
            return '/storage.php?file=' . $this->image . '&t=' . $mtime;
        }
        return null;
    }
    
    public function getDisplayFilenameAttribute()
    {
        return $this->original_filename ?? ($this->image ? basename($this->image) : null);
    }
    
    /**
     * Check if content has an image
     */
    public function hasImage()
    {
        return !is_null($this->image);
    }
}