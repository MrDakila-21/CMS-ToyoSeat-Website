<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $table = 'company_locations';
    
    protected $fillable = [
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'google_maps_embed',
        'latitude',
        'longitude',
        'phone',
        'email',
        'working_hours',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public static function getActiveLocation()
    {
        return self::where('is_active', true)->first();
    }

    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ];
        
        return implode(', ', array_filter($parts));
    }

    // Scope for active locations
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}