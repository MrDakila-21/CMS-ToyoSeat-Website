<?php

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

    protected $appends = ['image_url', 'image_url_legacy'];

    // For guest view - uses storage.php with cache-busting
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Add cache-busting parameter using updated_at timestamp
            $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
            return '/storage.php?file=' . urlencode($this->image) . '&v=' . $timestamp;
        }
        return null;
    }
    
    // For admin view - with cache-busting
    public function getImageUrlLegacyAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
            return Storage::url($this->image) . '?v=' . $timestamp;
        }
        return null;
    }
    
    // Force cache bust when model is updated
    public static function boot()
    {
        parent::boot();
        
        static::updated(function ($model) {
            // Touch the updated_at timestamp to force cache bust
            $model->updateTimestamps();
        });
    }
}