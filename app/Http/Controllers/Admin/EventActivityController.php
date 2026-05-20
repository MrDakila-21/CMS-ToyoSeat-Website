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
            // The accessor will automatically check for folder images first
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            // Store with ID as filename in storage
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$eventActivity->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('events-activities', $filename, 'public');
            $eventActivity->image = $imagePath;
            $eventActivity->save();
        } else {
            // Check if there's an image in the folder with matching ID
            // This will clear the database path if folder image exists
            $eventActivity->syncImageFromFolder();
        }

        // Refresh to get latest data including image_url
        $eventActivity->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Event/Activity created successfully!', 
                'data' => $eventActivity,
                'image_url' => $eventActivity->image_url,
                'timestamp' => time()
            ]);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:event,activity'
        ]);

        // Update fields
        $eventActivity->title = $request->title;
        $eventActivity->description = $request->description;
        $eventActivity->event_date = $request->event_date;
        $eventActivity->type = $request->type;

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete any existing folder image first
            $eventActivity->deleteFolderImage();
            
            // Delete old database image if exists in storage
            if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
                Storage::disk('public')->delete($eventActivity->image);
            }
            
            // Upload new image with ID as filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$eventActivity->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('events-activities', $filename, 'public');
            $eventActivity->image = $imagePath;
        } else {
            // If no new image uploaded, check if folder image exists
            // This ensures folder images take priority
            $eventActivity->syncImageFromFolder();
        }

        // Save updates
        $eventActivity->save();
        
        // Refresh to get latest data
        $eventActivity->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Event/Activity updated successfully!',
                'image_url' => $eventActivity->image_url,
                'timestamp' => time(),
                'updated_at' => $eventActivity->updated_at->timestamp
            ]);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'media'])
            ->with('success', 'Event/Activity updated successfully!');
    }

    // Delete record
    public function destroy($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        
        // Delete database image file if exists in storage
        if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
            Storage::disk('public')->delete($eventActivity->image);
        }
        
        // Delete folder image if exists
        $eventActivity->deleteFolderImage();
        
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
        
        $eventActivity->refresh();

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully',
            'image_url' => $eventActivity->image_url,
            'timestamp' => time()
        ]);
    }
    
    // Upload image directly to EventActivity folder by ID
    public function uploadDirectImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:events_activities,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $eventActivity = EventActivity::find($request->id);
        
        // Delete any existing folder image first (to replace it)
        $eventActivity->deleteFolderImage();
        
        // Delete any database stored image if exists
        if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
            Storage::disk('public')->delete($eventActivity->image);
        }
        
        // Store the image directly in public/events-activities folder with ID as filename
        $extension = $request->file('image')->getClientOriginalExtension();
        $filename = "{$eventActivity->id}.{$extension}";
        
        // Create events-activities directory if it doesn't exist in public
        $eventActivityPath = public_path('events-activities');
        if (!file_exists($eventActivityPath)) {
            mkdir($eventActivityPath, 0755, true);
        }
        
        // Move the uploaded file
        $request->file('image')->move($eventActivityPath, $filename);
        
        // Clear the database image path (so it uses the folder image)
        $eventActivity->image = null;
        $eventActivity->save();
        
        // Refresh to get latest data
        $eventActivity->refresh();
        
        // Get file modification time for cache-busting
        $timestamp = filemtime($eventActivityPath . '/' . $filename);
        
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully! The folder image will now take priority.',
            'image_url' => $eventActivity->image_url,
            'timestamp' => $timestamp
        ]);
    }
    
    // Sync all images from EventActivity folder
    public function syncAllImages()
    {
        $updated = EventActivity::syncAllImagesFromFolder();
        
        return response()->json([
            'success' => true,
            'message' => "Synced {$updated} items successfully! Images from events-activities folder will now take priority.",
            'timestamp' => time()
        ]);
    }
    
    // Upload multiple images at once to EventActivity folder (batch upload)
    public function batchUploadToFolder(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $uploaded = [];
        $failed = [];
        
        // Create events-activities directory if it doesn't exist
        $eventActivityPath = public_path('events-activities');
        if (!file_exists($eventActivityPath)) {
            mkdir($eventActivityPath, 0755, true);
        }
        
        foreach ($request->file('images') as $file) {
            // Get the original filename without extension
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            // Check if the filename is numeric (ID)
            if (is_numeric($originalName)) {
                $id = $originalName;
                $eventActivity = EventActivity::find($id);
                
                if ($eventActivity) {
                    // Delete any existing folder image for this ID
                    $eventActivity->deleteFolderImage();
                    
                    // Delete any database stored image
                    if ($eventActivity->image && Storage::disk('public')->exists($eventActivity->image)) {
                        Storage::disk('public')->delete($eventActivity->image);
                    }
                    
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$id}.{$extension}";
                    
                    $file->move($eventActivityPath, $filename);
                    
                    // Clear database image path
                    $eventActivity->image = null;
                    $eventActivity->save();
                    
                    $eventActivity->refresh();
                    
                    $uploaded[] = [
                        'id' => $id,
                        'title' => $eventActivity->title,
                        'image_url' => $eventActivity->image_url,
                        'timestamp' => filemtime($eventActivityPath . '/' . $filename)
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
            'message' => "Uploaded: " . count($uploaded) . " files, Failed: " . count($failed) . ". Images saved to events-activities folder.",
            'uploaded' => $uploaded,
            'failed' => $failed,
            'timestamp' => time()
        ]);
    }
    
    // Remove folder image and revert to database image
    public function removeFolderImage($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        
        // Delete the folder image
        $deleted = $eventActivity->deleteFolderImage();
        
        if ($deleted) {
            $eventActivity->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Folder image removed. System will now use the database image (if any) or default image.',
                'image_url' => $eventActivity->image_url,
                'timestamp' => time()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No folder image found for this item.'
            ], 404);
        }
    }
    
    // Get all folder images info
    public function getFolderImagesInfo()
    {
        $eventActivityPath = public_path('events-activities');
        $images = [];
        
        if (file_exists($eventActivityPath)) {
            $files = scandir($eventActivityPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $id = pathinfo($file, PATHINFO_FILENAME);
                    
                    if (is_numeric($id)) {
                        $eventActivity = EventActivity::find($id);
                        $images[] = [
                            'filename' => $file,
                            'id' => $id,
                            'title' => $eventActivity ? $eventActivity->title : 'Unknown',
                            'exists_in_db' => $eventActivity ? true : false,
                            'size' => filesize($eventActivityPath . '/' . $file),
                            'modified' => date('Y-m-d H:i:s', filemtime($eventActivityPath . '/' . $file))
                        ];
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'folder' => 'events-activities',
            'total_images' => count($images),
            'images' => $images,
            'timestamp' => time()
        ]);
    }
    
    // Get fresh image URL for a specific item (for AJAX refresh)
    public function getFreshImageUrl($id)
    {
        $eventActivity = EventActivity::findOrFail($id);
        $eventActivity->refresh();
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'image_url' => $eventActivity->image_url,
            'timestamp' => time()
        ]);
    }
}