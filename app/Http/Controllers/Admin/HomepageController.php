<?php
// app/Http/Controllers/Admin/HomepageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomepageController extends Controller
{
    /**
     * Get current homepage background image
     */
    public function getImage()
    {
        try {
            // Change from 'main_image' to 'hero_background' for consistency
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
                'message' => 'Failed to fetch image'
            ], 500);
        }
    }
    
    /**
 * Upload or update homepage background image
 */
public function uploadImage(Request $request)
{
    try {
        $request->validate([
            'background_image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120' // 5MB max
        ]);
        
        $image = $request->file('background_image');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));
        
        // Update or create record - use 'hero_background' consistently
        Homepage::updateOrCreate(
            ['key' => 'hero_background'],
            ['image_data' => $imageData]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Background image uploaded successfully!'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Check if it's a file size error
        $errorMessage = $e->errors()['background_image'][0] ?? 'Invalid image file';
        
        // Make the error message more specific
        if (str_contains($errorMessage, 'max')) {
            $errorMessage = 'File exceeds the 5MB size limit. Please choose a smaller file.';
        }
        
        return response()->json([
            'success' => false,
            'message' => $errorMessage
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error uploading homepage image: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload image. Please try again.'
        ], 500);
    }
}
    
   /**
 * Remove homepage background image
 */
public function removeImage()
{
    try {
        // Check if image exists first
        $image = Homepage::where('key', 'hero_background')->first();
        
        if (!$image || !$image->image_data) {
            return response()->json([
                'success' => false,
                'message' => 'No background image to remove.'
            ], 404); // Use 404 status code to indicate not found
        }
        
        // Delete the image
        $image->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Background image removed successfully. Default GIF will be used.'
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