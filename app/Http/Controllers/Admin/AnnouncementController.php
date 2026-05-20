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

    // Store new record
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

        // Handle image upload if present (store with ID as filename)
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$announcement->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('announcements', $filename, 'public');
            $announcement->image = $imagePath;
            $announcement->save();
        } else {
            // Check if there's an image in the folder with matching ID
            $announcement->syncImageFromFolder();
        }

        // Refresh to get latest data
        $announcement->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Announcement created successfully!', 
                'data' => $announcement,
                'image_url' => $announcement->image_url,
                'timestamp' => time()
            ]);
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

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete any existing folder image first
            $announcement->deleteFolderImage();
            
            // Delete old database image if exists in storage
            if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }
            
            // Upload new image with ID as filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$announcement->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('announcements', $filename, 'public');
            $announcement->image = $imagePath;
        } else {
            // If no new image uploaded, check if folder image exists
            $announcement->syncImageFromFolder();
        }

        $announcement->save();
        
        // Refresh to get latest data
        $announcement->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Announcement updated successfully!',
                'image_url' => $announcement->image_url,
                'timestamp' => time()
            ]);
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
        
        $announcement->refresh();

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully',
            'image_url' => $announcement->image_url,
            'timestamp' => time()
        ]);
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
        
        // Refresh to get latest data
        $announcement->refresh();
        
        // Get file modification time
        $announcementPath = public_path("images/announcements/{$announcement->id}.*");
        $files = glob($announcementPath);
        $timestamp = !empty($files) ? filemtime($files[0]) : time();
        
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully! This will now be the primary image.',
            'image_url' => $announcement->image_url,
            'timestamp' => $timestamp
        ]);
    }
    
    // Get fresh image URL for a specific item
    public function getFreshImageUrl($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->refresh();
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'image_url' => $announcement->image_url,
            'timestamp' => time()
        ]);
    }
}