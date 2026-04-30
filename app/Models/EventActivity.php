<?php

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

    // Helper to get full image URL
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        return null;
    }
}