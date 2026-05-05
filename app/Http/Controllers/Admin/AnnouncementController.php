<?php
// app/Http/Controllers/Admin/AnnouncementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    // Display all records
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'announcements']);
    }

    // Get ALL records for AJAX
    public function getAll()
    {
        $announcements = Announcement::orderBy('date', 'desc')->get();
        
        // Add image_url for each announcement
        $announcements->each(function($announcement) {
            $announcement->image_url = $announcement->image_url;
        });
        
        return response()->json($announcements);
    }

    // Store new record (Normal way - from form)
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Create new record
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        $announcement->date = $request->date;
        $announcement->status = 'published';

        // Save to get the ID first
        $announcement->save();

        // Handle normal image upload if present (saves to storage)
        if ($request->hasFile('image')) {
            $announcement->saveImage($request->file('image'));
        }
        // If no image uploaded, it will use default-image.png or check public folder later

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Announcement created successfully!', 'data' => $announcement]);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'announcements'])
            ->with('success', 'Announcement created successfully!');
    }

    // Get record for editing
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->image_url = $announcement->image_url;
        
        return response()->json($announcement);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Update fields
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        $announcement->date = $request->date;

        // Handle normal image upload if present (saves to storage)
        if ($request->hasFile('image')) {
            $announcement->saveImage($request->file('image'));
        }

        $announcement->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Announcement updated successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'announcements'])
            ->with('success', 'Announcement updated successfully!');
    }

    // Delete record
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Announcement deleted successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'news', 'subtab' => 'announcements'])
            ->with('success', 'Announcement deleted successfully!');
    }

    // Update status (published/draft/archived)
    public function updateStatus($id, $status)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = $status;
        $announcement->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
    // DIRECT UPLOAD: Upload image directly to public folder for existing announcement
    public function uploadDirectImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:announcements,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $announcement = Announcement::find($request->id);
        $announcement->saveImageToPublicFolder($request->file('image'));
        
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded directly to announcements folder! This will now be the primary image.',
            'image_url' => $announcement->fresh()->image_url
        ]);
    }
}
