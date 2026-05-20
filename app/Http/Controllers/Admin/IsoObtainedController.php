<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IsoObtained;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class IsoObtainedController extends Controller
{
    // Get all ISO entries
    public function index()
    {
        $isoEntries = IsoObtained::orderBy('created_at', 'desc')->get();
        $isoEntries->each(function ($iso) {
            $iso->image_url = $iso->image_url;
        });

        return response()->json($isoEntries);
    }

    // Store new ISO entry
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'status' => 'nullable|in:published,archived',
            ]);

            $iso = new IsoObtained;
            $iso->title = $request->title;
            $iso->description = $request->description;
            $iso->status = $request->input('status', 'published');
            $iso->is_active = true;

            // If creating as intro, ensure it's flagged and unset others
            if ($request->boolean('is_intro')) {
                if (Schema::hasColumn('iso_obtained', 'is_intro')) {
                    IsoObtained::query()->update(['is_intro' => false]);
                    $iso->is_intro = true;
                }
            } else {
                $iso->is_intro = false;
            }

            $iso->save();

            if ($request->hasFile('image')) {
                $iso->saveImage($request->file('image'));
            }

            return response()->json([
                'success' => true,
                'message' => 'ISO Obtained entry created successfully!',
                'data' => $iso,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Get single entry for editing
    public function edit($id)
    {
        try {
            $iso = IsoObtained::findOrFail($id);
            $iso->image_url = $iso->image_url;

            return response()->json($iso);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entry not found.',
            ], 404);
        }
    }

    // Update ISO entry
    public function update(Request $request, $id)
    {
        try {
            $iso = IsoObtained::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'status' => 'nullable|in:published,archived',
            ]);

            $iso->title = $request->title;
            $iso->description = $request->description;
            if ($request->filled('status')) {
                $iso->status = $request->status;
            }

            // If this update marks this entry as intro, unset others
            if ($request->boolean('is_intro')) {
                if (Schema::hasColumn('iso_obtained', 'is_intro')) {
                    IsoObtained::where('id', '!=', $iso->id)->update(['is_intro' => false]);
                    $iso->is_intro = true;
                }
            } else {
                $iso->is_intro = false;
            }

            if ($request->hasFile('image')) {
                $iso->saveImage($request->file('image'));
            }

            $iso->save();

            return response()->json([
                'success' => true,
                'message' => 'ISO Obtained entry updated successfully!',
                'data' => $iso,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Delete ISO entry
    public function destroy($id)
    {
        try {
            $iso = IsoObtained::findOrFail($id);
            $iso->deleteAllImages();
            $iso->delete();

            return response()->json([
                'success' => true,
                'message' => 'ISO Obtained entry deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Remove image from entry
    public function removeImage($id)
    {
        try {
            $iso = IsoObtained::findOrFail($id);
            $iso->deleteAllImages();
            $iso->image = null;
            $iso->save();

            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully!',
                'image_url' => $iso->image_url, // Return new image URL for UI update
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Update status (published/archived)
    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['published', 'archived'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value.',
            ], 422);
        }

        $iso = IsoObtained::findOrFail($id);
        $iso->status = $status;
        $iso->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }
}