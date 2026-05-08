<?php
// app/Http/Controllers/Guest/AnnouncementController.php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        // Get published announcements with pagination
        $announcements = Announcement::where('status', 'published')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $announcements = Announcement::where('status', 'published')
                ->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(8);
        }

        // For AJAX requests, return only the view without layout
        if ($request->ajax()) {
            return view('guest.news.announcements', ['announcements' => $announcements])->render();
        }

        // For normal page load, return the full view with announcements
        return view('guest.news.announcements', ['announcements' => $announcements]);
    }

    public function show($id)
    {
        $announcement = Announcement::where('status', 'published')->findOrFail($id);
        return response()->json($announcement);
    }
}