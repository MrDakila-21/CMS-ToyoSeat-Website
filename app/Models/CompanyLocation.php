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
        'telephone',
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

    // New method to parse working hours with custom titles
    public function getParsedWorkingHoursAttribute()
    {
        if (empty($this->working_hours)) {
            return [];
        }

        $hours = [];
        $lines = explode("\n", $this->working_hours);
        
        foreach ($lines as $line) {
            if (trim($line)) {
                // Support format: "Title|Day Range: Time" or "Day Range: Time"
                if (strpos($line, '|') !== false) {
                    $parts = explode('|', $line, 2);
                    $title = trim($parts[0]);
                    $rest = trim($parts[1]);
                    
                    if (strpos($rest, ':') !== false) {
                        $timeParts = explode(':', $rest, 2);
                        $dayRange = trim($timeParts[0]);
                        $timeRange = trim($timeParts[1]);
                        
                        $hours[] = [
                            'title' => $title,
                            'day_range' => $dayRange,
                            'time' => $timeRange
                        ];
                    }
                } else if (strpos($line, ':') !== false) {
                    $parts = explode(':', $line, 2);
                    $hours[] = [
                        'title' => null,
                        'day_range' => trim($parts[0]),
                        'time' => trim($parts[1])
                    ];
                }
            }
        }
        
        return $hours;
    }
}