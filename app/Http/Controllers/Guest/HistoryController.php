<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    /**
     * Display admin history management page
     */
    public function index()
    {
        return view('admin.partials.about.history');
    }
    
    /**
     * Get all history records for admin (AJAX)
     */
    public function getAll()
    {
        try {
            $histories = History::orderBy('date', 'desc')->get();
            
            $histories->transform(function ($history) {
                return [
                    'id' => $history->id,
                    'title' => $history->title,
                    'description' => $history->description,
                    'date' => $history->date,
                    'image' => $history->image,
                    'image_url' => $history->image_url,
                    'status' => $history->status,
                    'created_at' => $history->created_at,
                    'updated_at' => $history->updated_at,
                ];
            });
            
            return response()->json($histories);
        } catch (\Exception $e) {
            \Log::error('Error fetching history records: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load history records'], 500);
        }
    }
    
    /**
     * Store a new history record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        $data['status'] = 'published';
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('history', 'public');
            $data['image'] = $path;
        }
        
        $history = History::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'History record created successfully',
            'data' => $history
        ]);
    }
    
    /**
     * Get history record for editing
     */
    public function edit($id)
    {
        try {
            $history = History::findOrFail($id);
            return response()->json([
                'id' => $history->id,
                'title' => $history->title,
                'description' => $history->description,
                'date' => $history->date,
                'image' => $history->image,
                'image_url' => $history->image_url,
                'status' => $history->status,
                'updated_at' => $history->updated_at
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Record not found'], 404);
        }
    }
    
    /**
     * Update history record
     */
    public function update(Request $request, $id)
    {
        $history = History::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_image' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image', 'remove_image');
        
        // Check if image should be removed
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($history->image && Storage::disk('public')->exists($history->image)) {
                Storage::disk('public')->delete($history->image);
            }
            $data['image'] = null;
        }
        // Check if new image is uploaded
        elseif ($request->hasFile('image')) {
            if ($history->image && Storage::disk('public')->exists($history->image)) {
                Storage::disk('public')->delete($history->image);
            }
            $file = $request->file('image');
            $path = $file->store('history', 'public');
            $data['image'] = $path;
        }
        
        $history->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'History record updated successfully',
            'data' => $history
        ]);
    }
    
    /**
     * Update status only
     */
    public function updateStatus($id, $status)
    {
        try {
            $history = History::findOrFail($id);
            $history->status = $status;
            $history->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
    
    /**
     * Delete history record
     */
    public function destroy($id)
    {
        try {
            $history = History::findOrFail($id);
            
            if ($history->image && Storage::disk('public')->exists($history->image)) {
                Storage::disk('public')->delete($history->image);
            }
            
            $history->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'History record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete history record'
            ], 500);
        }
    }
}