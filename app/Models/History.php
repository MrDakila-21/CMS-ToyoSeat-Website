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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        
        return null;
    }
}