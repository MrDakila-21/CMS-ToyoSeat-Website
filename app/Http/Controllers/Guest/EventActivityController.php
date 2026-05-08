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
        
        // If AJAX request for real-time filtering, return paginated results as JSON
        if ($request->ajax()) {
            $events = $query->paginate(9);
            
            // Ensure each event has image_url
            $items = $events->items();
            foreach ($items as $event) {
                $event->image_url = $event->image_url; // This triggers the accessor
            }
            
            return response()->json([
                'data' => $items,
                'total' => $events->total(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
            ]);
        }
        
        // Regular request - paginate normally
        $events = $query->paginate(9);
        
        return view('guest.news.media-information', compact('events'));
    }
    
    // Get single event details via AJAX
    public function show($id)
    {
        $event = EventActivity::where('status', 'published')->findOrFail($id);
        $event->image_url = $event->image_url; // Ensure image_url is included
        return response()->json($event);
    }
    
    public function boot()
    {
        Paginator::useBootstrapFive();
    }
}