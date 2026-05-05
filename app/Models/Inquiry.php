<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime',
    ];
}