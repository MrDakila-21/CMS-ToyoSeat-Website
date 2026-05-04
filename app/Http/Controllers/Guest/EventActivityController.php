<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class EventActivityController extends Controller
{
    // Display events and activities to guests with pagination
    public function index(Request $request)
    {
        $query = EventActivity::where('status', 'published')
            ->orderBy('event_date', 'desc');
        
        // Apply search if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply type filter if provided
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Paginate results (9 per page)
        $events = $query->paginate(9);
        
        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json($events);
        }
        
        return view('guest.news.media-information', compact('events'));
    }
    
    // Get single event details via AJAX
    public function show($id)
    {
        $event = EventActivity::where('status', 'published')->findOrFail($id);
        return response()->json($event);
    }
    public function boot()
{
    Paginator::useBootstrapFive(); // or useBootstrapFour()
}
}