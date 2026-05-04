<?php
// app/Http/Controllers/Admin/EventActivityController.php

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

    // Get ALL records for AJAX
    public function getAll()
    {
        $events = EventActivity::orderBy('event_date', 'desc')->get();
        
        // Add image_url for each event using the accessor
        $events->each(function($event) {
            // The accessor will automatically check for folder images
            $event->image_url = $event->image_url;
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

        // Save to get the ID first
        $eventActivity->save();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Store with ID as filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$eventActivity->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('events-activities', $filename, 'public');
            $eventActivity->image = $imagePath;
            $eventActivity->save();
        } else {
            // Check if there's an image in the folder with matching ID
            $eventActivity->syncImageFromFolder();
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Event/Activity created successfully!', 'data' => $eventActivity]);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity created successfully!');
    }

    // Get record for editing
    public function edit($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        
        // Add image_url for display (accessor handles folder images)
        $eventActivity->image_url = $eventActivity->image_url;
        
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
            // Delete old image if exists in storage
            if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
                Storage::disk('public')->delete($eventActivity->image);
            }
            
            // Upload new image with ID as filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$eventActivity->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('events-activities', $filename, 'public');
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
        
        // Delete image file if exists in storage
        if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
            Storage::disk('public')->delete($eventActivity->image);
        }
        
        // Note: We don't delete images from the public/images folder as they might be used elsewhere
        
        // Delete record from database
        $eventActivity->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Event/Activity deleted successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity deleted successfully!');
    }

    // Update status (published/archived)
    public function updateStatus($id, $status)
    {
        $eventActivity = EventActivity::findOrFail($id);
        $eventActivity->status = $status;
        $eventActivity->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
    // NEW METHOD: Upload image directly to folder by ID
    public function uploadDirectImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:events_activities,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);
        
        $eventActivity = EventActivity::find($request->id);
        
        // Store the image directly in public/images folder with ID as filename
        $extension = $request->file('image')->getClientOriginalExtension();
        $filename = "{$eventActivity->id}.{$extension}";
        
        // Create images directory if it doesn't exist
        $imagesPath = public_path('images');
        if (!file_exists($imagesPath)) {
            mkdir($imagesPath, 0755, true);
        }
        
        // Move the uploaded file
        $request->file('image')->move($imagesPath, $filename);
        
        // Clear the database image path if exists (so it uses the folder image)
        if ($eventActivity->image) {
            $eventActivity->image = null;
            $eventActivity->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully to folder!',
            'image_url' => $eventActivity->fresh()->image_url
        ]);
    }
    
    // NEW METHOD: Sync all images from folder
    public function syncAllImages()
    {
        $updated = EventActivity::syncAllImagesFromFolder();
        
        return response()->json([
            'success' => true,
            'message' => "Synced {$updated} items successfully!"
        ]);
    }
    
    // NEW METHOD: Upload multiple images at once to folder (batch upload)
    public function batchUploadToFolder(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);
        
        $uploaded = [];
        $failed = [];
        
        foreach ($request->file('images') as $file) {
            // Get the original filename without extension
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            // Check if the filename is numeric (ID)
            if (is_numeric($originalName)) {
                $id = $originalName;
                $eventActivity = EventActivity::find($id);
                
                if ($eventActivity) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$id}.{$extension}";
                    
                    $imagesPath = public_path('images');
                    if (!file_exists($imagesPath)) {
                        mkdir($imagesPath, 0755, true);
                    }
                    
                    $file->move($imagesPath, $filename);
                    
                    // Clear database image path
                    if ($eventActivity->image) {
                        $eventActivity->image = null;
                        $eventActivity->save();
                    }
                    
                    $uploaded[] = [
                        'id' => $id,
                        'title' => $eventActivity->title,
                        'image_url' => $eventActivity->fresh()->image_url
                    ];
                } else {
                    $failed[] = [
                        'filename' => $file->getClientOriginalName(),
                        'reason' => "No event/activity found with ID: {$id}"
                    ];
                }
            } else {
                $failed[] = [
                    'filename' => $file->getClientOriginalName(),
                    'reason' => "Filename must be numeric (ID), e.g., 1.jpg, 2.png"
                ];
            }
        }
        
        return response()->json([
            'success' => count($uploaded) > 0,
            'message' => "Uploaded: " . count($uploaded) . " files, Failed: " . count($failed),
            'uploaded' => $uploaded,
            'failed' => $failed
        ]);
    }
}