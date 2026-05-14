<?php
// app/Models/BusinessContent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessContent extends Model
{
    protected $table = 'business_contents';
    
    protected $fillable = [
        'section', 'title', 'description', 'image', 
        'name', 'position', 'order', 'is_active'
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
    
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        return null;
    }
}