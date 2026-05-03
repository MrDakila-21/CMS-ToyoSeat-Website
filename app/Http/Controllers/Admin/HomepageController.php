<?php
// app/Http/Controllers/Admin/HomepageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use App\Models\HomepageSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomepageController extends Controller
{
    /**
     * Get all slideshow images
     */
    public function getSlides()
    {
        try {
            $slides = HomepageSlide::orderBy('order', 'asc')
                ->get()
                ->map(function($slide) {
                    return [
                        'id' => $slide->id,
                        'image_url' => asset('storage/' . $slide->image_path),
                        'order' => $slide->order,
                        'is_active' => $slide->is_active
                    ];
                });

            return response()->json([
                'success' => true,
                'slides' => $slides,
                'has_slides' => $slides->count() > 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching slides: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch slides: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload multiple images for slideshow
     */
    public function uploadMultipleImages(Request $request)
    {
        try {
            Log::info('Upload multiple images request received');
            
            // Validate the request
            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files were uploaded. Please select at least one image.',
                ], 422);
            }
            
            $files = $request->file('images');
            
            // Custom validation
            if (count($files) > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only upload up to 10 images at once.',
                ], 422);
            }
            
            $uploadedImages = [];
            $currentMaxOrder = HomepageSlide::max('order') ?? 0;
            
            foreach ($files as $index => $image) {
                // Validate each file
                if (!$image->isValid()) {
                    Log::warning('Invalid file at index ' . $index);
                    continue;
                }
                
                $extension = strtolower($image->getClientOriginalExtension());
                $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
                
                if (!in_array($extension, $allowedExtensions)) {
                    Log::warning('Invalid file type: ' . $extension);
                    continue;
                }
                
                if ($image->getSize() > 5 * 1024 * 1024) {
                    Log::warning('File too large: ' . $image->getSize());
                    continue;
                }
                
                // Generate unique filename
                $filename = Str::random(40) . '.' . $extension;
                $path = $image->storeAs('homepage_slides', $filename, 'public');
                
                if (!$path) {
                    Log::error('Failed to store file at index ' . $index);
                    continue;
                }
                
                $slide = HomepageSlide::create([
                    'image_path' => $path,
                    'order' => $currentMaxOrder + $index + 1,
                    'is_active' => false
                ]);
                
                $uploadedImages[] = [
                    'id' => $slide->id,
                    'image_url' => asset('storage/' . $path),
                    'order' => $slide->order
                ];
            }
            
            if (count($uploadedImages) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid images were uploaded. Please check file types (JPG, PNG, GIF, WEBP) and size (max 5MB).',
                ], 422);
            }
            
            Log::info('Successfully uploaded ' . count($uploadedImages) . ' images');
            
            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' image(s) uploaded successfully!',
                'images' => $uploadedImages
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading multiple images: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update slides order (for reordering)
     */
    public function updateSlidesOrder(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'slides' => 'required|array',
                'slides.*.id' => 'required|exists:homepage_slides,id',
                'slides.*.order' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided: ' . $validator->errors()->first(),
                ], 422);
            }

            foreach ($request->slides as $slideData) {
                HomepageSlide::where('id', $slideData['id'])
                    ->update(['order' => $slideData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Images reordered successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating slides order: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
 * Present selected images (activate slideshow)
 */
public function presentSlides(Request $request)
{
    try {
        $slideIds = $request->slide_ids ?? [];
        
        // Deactivate all slides first
        HomepageSlide::query()->update(['is_active' => false]);
        
        // If there are selected slides, activate them
        if (!empty($slideIds) && count($slideIds) > 0) {
            // Validate that the IDs exist
            $validIds = HomepageSlide::whereIn('id', $slideIds)->pluck('id')->toArray();
            
            if (count($validIds) > 0) {
                // Activate selected slides
                HomepageSlide::whereIn('id', $validIds)
                    ->update(['is_active' => true]);
                
                $message = count($validIds) . ' image(s) are now active in the slideshow.';
            } else {
                $message = 'No valid images found. The homepage will use the default background image.';
            }
        } else {
            $message = 'Slideshow cleared. The homepage will now use the default background image.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error presenting slides: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update slideshow: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Delete individual slide
     */
    public function deleteSlide($id)
    {
        try {
            $slide = HomepageSlide::findOrFail($id);
            
            // Delete the file from storage
            if (Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }
            
            $slide->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting slide: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current homepage background image (legacy - keep for compatibility)
     */
    public function getImage()
    {
        try {
            $image = Homepage::where('key', 'hero_background')->first();

            if ($image && $image->image_data && !empty($image->image_data)) {
                return response()->json([
                    'success' => true,
                    'has_image' => true,
                    'image_data' => $image->image_data,
                ]);
            }

            return response()->json([
                'success' => true,
                'has_image' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching homepage image: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch current image. Please refresh and try again.',
            ], 500);
        }
    }
}