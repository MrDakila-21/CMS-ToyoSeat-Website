<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\IsoObtained;

class IsoObtainedController extends Controller
{
    public function index()
    {
        $isoContent = IsoObtained::first();

        return view('guest.about.iso-obtained', compact('isoContent'));
    }
}
