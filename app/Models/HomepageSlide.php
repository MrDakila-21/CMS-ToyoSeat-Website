<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSlide extends Model
{
    protected $table = 'homepage_slides';
    
    protected $fillable = [
        'image_path',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
    
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return '/storage.php?file=' . $this->image_path;
        }
        return '/storage.php?file=images/default-image.png';
    }
}