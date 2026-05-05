<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\CompanyLocation;

class LocationController extends Controller
{
    public function index()
    {
        $location = CompanyLocation::getActiveLocation();
        return view('guest.about.location', compact('location'));
    }
}