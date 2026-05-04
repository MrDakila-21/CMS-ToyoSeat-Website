<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverviewContent extends Model
{
    protected $table = 'overview_contents';
    
    protected $fillable = [
        'business_principles',
        'president_message',
        'president_name',
        'president_title',
        'president_image',
        'company_profile',
        'company_profile_image',
        'company_name',
        'established_date',
        'capital',
        'president_representative',
        'business_description',
        'employees',
        'dynamic_categories'  // Add this
    ];
    
    protected $casts = [
        'business_principles' => 'array',
        'dynamic_categories' => 'array'  // Add this
    ];
    
    public static function getContent()
    {
        $content = self::first();
        if (!$content) {
            $content = self::create([
                'business_principles' => [],
                'president_message' => 'Default message from the president...',
                'president_name' => 'President Name',
                'president_title' => 'President & CEO',
                'company_profile' => 'Company profile content...',
                'company_name' => 'Toyo Seat Philippines Corporation',
                'established_date' => '1994',
                'capital' => 'PHP 500 Million',
                'president_representative' => 'Mr. John Doe',
                'business_description' => 'Manufacturing and sales of automotive seats',
                'employees' => 1000,
                'dynamic_categories' => []  // Add this
            ]);
        }
        return $content;
    }
}