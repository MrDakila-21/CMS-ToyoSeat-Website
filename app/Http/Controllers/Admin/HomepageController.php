<?php
// app/Http/Controllers/Admin/HomepageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HomepageController extends Controller
{
    /**
     * Get current homepage background image
     */
    public function getImage()
    {
        try {
            $image = Homepage::where('key', 'hero_background')->first();
            
            if ($image && $image->image_data) {
                return response()->json([
                    'success' => true,
                    'has_image' => true,
                    'image_data' => $image->image_data
                ]);
            }
            
            return response()->json([
                'success' => true,
                'has_image' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching homepage image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch current image. Please refresh and try again.'
            ], 500);
        }
    }
    
    /**
     * Upload or update homepage background image
     */
    public function uploadImage(Request $request)
    {
        
        try {
            // Validate the request - this will throw ValidationException if fails
            $validated = $request->validate([
                'background_image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120'
            ]);
            
            $image = $request->file('background_image');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            
            Homepage::updateOrCreate(
                ['key' => 'hero_background'],
                ['image_data' => $imageData]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Background image uploaded successfully!'
            ]);
            
        } catch (ValidationException $e) {
            // This catches validation errors specifically
            $errors = $e->errors();
            $errorMessage = 'Invalid file';
            
            if (isset($errors['background_image'])) {
                $errorMessage = $errors['background_image'][0];
                
                // Make the error message more user-friendly
                if (str_contains($errorMessage, 'max')) {
                    $errorMessage = 'File exceeds the 5MB size limit. Maximum size is 5MB.';
                } elseif (str_contains($errorMessage, 'mimes')) {
                    $errorMessage = 'Invalid file type. Only JPG, PNG, GIF, and WEBP images are allowed.';
                } elseif (str_contains($errorMessage, 'required')) {
                    $errorMessage = 'Please select an image file to upload.';
                } elseif (str_contains($errorMessage, 'image')) {
                    $errorMessage = 'The file must be an image. Please upload a valid image file.';
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 422);
            
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            Log::error('Error uploading homepage image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: Unable to process the image. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Remove homepage background image
     */
    public function removeImage()
    {
        try {
            $image = Homepage::where('key', 'hero_background')->first();
            
            if (!$image || !$image->image_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No background image found to remove.'
                ], 404);
            }
            
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Background image removed successfully. Default picture will now be displayed.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing homepage image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image. Please try again.'
            ], 500);
        }
    }
}