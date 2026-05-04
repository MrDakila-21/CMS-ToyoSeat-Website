<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventActivityController extends Controller
{
    // Display all records
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media']);
    }

    // ADD THIS NEW METHOD - Get ALL records for AJAX
    public function getAll()
    {
        $events = EventActivity::orderBy('event_date', 'desc')->get();
        
        // Add image_url for each event
        $events->each(function($event) {
            if ($event->image) {
                $event->image_url = Storage::url($event->image);
            }
        });
        
        return response()->json($events);
    }

    // Store new record
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|in:event,activity'
        ]);

        // Create new record
        $eventActivity = new EventActivity();
        $eventActivity->title = $request->title;
        $eventActivity->description = $request->description;
        $eventActivity->event_date = $request->event_date;
        $eventActivity->type = $request->type;
        $eventActivity->status = 'published'; // Default status

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events-activities', 'public');
            $eventActivity->image = $imagePath;
        }

        // Save to database
        $eventActivity->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Event/Activity created successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity created successfully!');
    }

    // Get record for editing
    public function edit($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        
        // Add image_url for display
        if ($eventActivity->image) {
            $eventActivity->image_url = Storage::url($eventActivity->image);
        }
        
        return response()->json($eventActivity);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $eventActivity = EventActivity::findOrFail($id);

        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|in:event,activity'
        ]);

        // Update fields
        $eventActivity->title = $request->title;
        $eventActivity->description = $request->description;
        $eventActivity->event_date = $request->event_date;
        $eventActivity->type = $request->type;

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
                Storage::disk('public')->delete($eventActivity->image);
            }
            
            // Upload new image
            $imagePath = $request->file('image')->store('events-activities', 'public');
            $eventActivity->image = $imagePath;
        }

        // Save updates
        $eventActivity->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Event/Activity updated successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity updated successfully!');
    }

    // Delete record
    public function destroy($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        
        // Delete image file if exists
        if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
            Storage::disk('public')->delete($eventActivity->image);
        }
        
        // Delete record from database
        $eventActivity->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Event/Activity deleted successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity deleted successfully!');
    }

    // Update status (published/archived) - REMOVED DRAFT
    public function updateStatus($id, $status)
    {
        $eventActivity = EventActivity::findOrFail($id);
        $eventActivity->status = $status;
        $eventActivity->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}