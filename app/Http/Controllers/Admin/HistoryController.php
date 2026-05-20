<?php
// app/Http/Controllers/Admin/HistoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'about', 'subtab' => 'history']);
    }

    public function getAll()
    {
        try {
            $histories = History::orderBy('date', 'desc')->get();
            
            // Transform the data
            $data = $histories->map(function($history) {
                return [
                    'id' => $history->id,
                    'title' => $history->title,
                    'description' => $history->description,
                    'date' => $history->date,
                    'status' => $history->status,
                    'image' => $history->image,
                    'image_url' => $history->image_url,
                    'created_at' => $history->created_at,
                    'updated_at' => $history->updated_at,
                ];
            });
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error in getAll: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $history = new History();
            $history->title = $request->title;
            $history->description = $request->description;
            $history->date = $request->date;
            $history->status = 'published';
            $history->save();

            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = "{$history->id}.{$extension}";
                $imagePath = $request->file('image')->storeAs('histories', $filename, 'public');
                $history->image = $imagePath;
                $history->save();
            } else {
                $history->syncImageFromFolder();
            }

            $history->refresh();

            return response()->json([
                'success' => true, 
                'message' => 'History record created successfully!', 
                'data' => $history,
                'image_url' => $history->image_url,
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $history = History::findOrFail($id);
            $history->refresh();
            
            return response()->json([
                'id' => $history->id,
                'title' => $history->title,
                'description' => $history->description,
                'date' => $history->date,
                'status' => $history->status,
                'image' => $history->image,
                'image_url' => $history->image_url,
                'created_at' => $history->created_at,
                'updated_at' => $history->updated_at,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in edit: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

 // In your HistoryController@update method, make sure you're returning the fresh image URL
public function update(Request $request, $id)
{
    try {
        $history = History::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $history->title = $request->title;
        $history->description = $request->description;
        $history->date = $request->date;

        if ($request->hasFile('image')) {
            if ($history->image && Storage::disk('public')->exists($history->image)) {
                Storage::disk('public')->delete($history->image);
            }
            
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = "{$history->id}.{$extension}";
            $imagePath = $request->file('image')->storeAs('histories', $filename, 'public');
            $history->image = $imagePath;
        }

        $history->save();
        
        // Force refresh the model to get new timestamps
        $history->refresh();

        return response()->json([
            'success' => true, 
            'message' => 'History record updated successfully!',
            'image_url' => $history->image_url, // This will have the new timestamp
            'id' => $history->id
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function destroy($id)
    {
        try {
            $history = History::findOrFail($id);
            
            // Delete database image file if exists
            if ($history->image && Storage::disk('public')->exists($history->image)) {
                Storage::disk('public')->delete($history->image);
            }
            
            // Delete folder image if exists
            $history->deleteFolderImage();
            
            $history->delete();
            
            return response()->json(['success' => true, 'message' => 'History record deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $history = History::findOrFail($id);
            $history->status = $status;
            $history->save();
            $history->refresh();
            
            return response()->json([
                'success' => true, 
                'message' => 'Status updated successfully',
                'image_url' => $history->image_url,
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // Upload image directly to public folder
    public function uploadDirectImage(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:histories,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);
            
            $history = History::findOrFail($request->id);
            $history->saveImageToPublicFolder($request->file('image'));
            $history->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully to histories folder!',
                'image_url' => $history->image_url,
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // Get fresh image URL
    public function getFreshImageUrl($id)
    {
        $history = History::findOrFail($id);
        $history->refresh();
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'image_url' => $history->image_url,
            'timestamp' => time()
        ]);
    }
}