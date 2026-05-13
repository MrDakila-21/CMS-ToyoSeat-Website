<?php
// app/Http/Controllers/Guest/RecruitmentController.php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('guest.recruitment.recruitment-information', compact('recruitments'));
    }
    
    public function getPublished()
    {
        $recruitments = Recruitment::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($recruitments);
    }
}