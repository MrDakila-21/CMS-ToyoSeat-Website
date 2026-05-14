<?php
// app/Http/Controllers/Guest/BusinessIntroductionController.php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\BusinessContent;

class BusinessIntroductionController extends Controller
{
    public function index()
    {
        $automotiveSeats = BusinessContent::getAutomotiveSeats();
        $organizationMembers = BusinessContent::getOrganizationMembers();
        $characteristics = BusinessContent::getCharacteristics();
        $partnerships = BusinessContent::getPartnerships();
        
        return view('guest.about.business-introduction', compact(
            'automotiveSeats', 'organizationMembers', 'characteristics', 'partnerships'
        ));
    }
}