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
     * Get all slideshow images (used by dashboard view)
     */
    public function getSlides()
    {
        try {
            $slides = HomepageSlide::orderBy('order', 'asc')
                ->get()
                ->map(function($slide) {
                    return [
                        'id' => $slide->id,
                        'image_url' => '/storage.php?file=' . $slide->image_path,
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
            
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type. AJAX request required.'
                ], 400);
            }
            
            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files were uploaded. Please select at least one image.'
                ], 400);
            }
            
            $files = $request->file('images');
            
            if (count($files) > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only upload up to 10 images at once.'
                ], 400);
            }
            
            $uploadedCount = 0;
            $currentMaxOrder = HomepageSlide::max('order') ?? 0;
            $errors = [];
            
            foreach ($files as $index => $image) {
                if (!$image->isValid()) {
                    $errors[] = 'Invalid file at position ' . ($index + 1);
                    continue;
                }
                
                $extension = strtolower($image->getClientOriginalExtension());
                $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'jfif']; // Added 'jfif'

                if (!in_array($extension, $allowedExtensions)) {
                    $errors[] = $image->getClientOriginalName() . ' has invalid file type. Allowed: JPG, PNG, GIF, WEBP, JFIF';
                    continue;
                }
                
                if ($image->getSize() > 10 * 1024 * 1024) {
                    $errors[] = $image->getClientOriginalName() . ' exceeds 10MB limit.';
                    continue;
                }
                
                $filename = Str::random(40) . '.' . $extension;
                $path = $image->storeAs('homepage_slides', $filename, 'public');
                
                if (!$path) {
                    $errors[] = 'Failed to store ' . $image->getClientOriginalName();
                    continue;
                }
                
                HomepageSlide::create([
                    'image_path' => $path,
                    'order' => $currentMaxOrder + $uploadedCount + 1,
                    'is_active' => false
                ]);
                
                $uploadedCount++;
            }
            
            if ($uploadedCount === 0) {
                $errorMessage = 'No valid images were uploaded. ' . implode(' ', $errors);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }
            
            $successMessage = $uploadedCount . ' image(s) uploaded successfully!';
            if (!empty($errors)) {
                $successMessage .= ' However, some files failed: ' . implode(' ', $errors);
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'warnings' => $errors
                ]);
            }
            
            Log::info('Successfully uploaded ' . $uploadedCount . ' images');
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading multiple images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update slides order (for reordering)
     */
    public function updateSlidesOrder(Request $request)
    {
        try {
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type. AJAX request required.'
                ], 400);
            }
            
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
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type. AJAX request required.'
                ], 400);
            }
            
            $slideIds = $request->slide_ids ?? [];
            
            HomepageSlide::query()->update(['is_active' => false]);
            
            if (!empty($slideIds) && count($slideIds) > 0) {
                $validIds = HomepageSlide::whereIn('id', $slideIds)->pluck('id')->toArray();
                
                if (count($validIds) > 0) {
                    HomepageSlide::whereIn('id', $validIds)
                        ->update(['is_active' => true]);
                    
                    $message = count($validIds) . ' image(s) are now active in the slideshow.';
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                } else {
                    $message = 'No valid images found. The homepage will use the default background image.';
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                }
            } else {
                $message = 'Slideshow cleared. The homepage will now use the default background image.';
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error presenting slides: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update slideshow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete individual slide
     */
    public function deleteSlide(Request $request, $id)
    {
        try {
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type. AJAX request required.'
                ], 400);
            }
            
            $slide = HomepageSlide::findOrFail($id);
            
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
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current homepage background image (legacy - keep for compatibility)
     */
    public function getImage(Request $request)
    {
        try {
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type.'
                ], 400);
            }
            
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

    /**
     * Upload background image (legacy - keep for compatibility)
     */
    public function uploadImage(Request $request)
    {
        try {
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type.'
                ], 400);
            }
            
            $request->validate([
                'background_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $image = $request->file('background_image');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));

            Homepage::updateOrCreate(
                ['key' => 'hero_background'],
                [
                    'image_data' => $imageData,
                    'image_mime' => $image->getMimeType()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Background image updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading background image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove background image (legacy - keep for compatibility)
     */
    public function removeImage(Request $request)
    {
        try {
            if (!$request->ajax() && !$request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request type.'
                ], 400);
            }
            
            Homepage::where('key', 'hero_background')->delete();

            return response()->json([
                'success' => true,
                'message' => 'Background image removed successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing background image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image: ' . $e->getMessage()
            ], 500);
        }
    }
}