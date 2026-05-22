<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display the history page
     */
    public function index()
    {
        return view('guest.about.history');
    }

    /**
     * Get all published history records for guest view
     */
    public function getPublished()
    {
        try {
            $histories = History::where('status', 'published')
                ->orderBy('date', 'asc')
                ->get()
                ->map(function($history) {
                    // Force the image_url to be generated properly
                    $imageUrl = null;
                    if ($history->image) {
                        // Use storage.php for serving images (same as overview)
                        $imageUrl = '/storage.php?file=' . urlencode($history->image);
                    }
                    
                    return [
                        'id' => $history->id,
                        'title' => $history->title,
                        'description' => $history->description,
                        'date' => $history->date,
                        'image' => $history->image,
                        'image_url' => $imageUrl, // Use the storage.php URL
                        'status' => $history->status,
                    ];
                });
            
            return response()->json($histories);
        } catch (\Exception $e) {
            \Log::error('Error in Guest HistoryController: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load history data'], 500);
        }
    }
}