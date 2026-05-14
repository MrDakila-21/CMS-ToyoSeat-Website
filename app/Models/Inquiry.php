<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact_number',  // NEW
        'company_name',    // NEW
        'subject',
        'message',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime',
    ];
}