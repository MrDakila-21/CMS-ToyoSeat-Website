<?php
// app/Models/Homepage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    protected $table = 'homepage';
    protected $fillable = ['key', 'image_data'];
}