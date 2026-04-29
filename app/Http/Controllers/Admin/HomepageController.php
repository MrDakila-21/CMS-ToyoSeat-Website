<?php

// app/Http/Controllers/Admin/HomepageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager; // Add this if using Intervention Image package

class HomepageController extends Controller
{
    /**
     * Get current homepage background image
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

    /**
     * Upload or update homepage background image
     */
    public function uploadImage(Request $request)
    {
        try {
            // Validate the request with more specific messages
            $validator = validator($request->all(), [
                'background_image' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'background_image.required' => 'Please select an image file to upload.',
                'background_image.file' => 'The uploaded file is invalid.',
                'background_image.image' => 'The file must be an image. Please upload a valid image file.',
                'background_image.mimes' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP images are allowed.',
                'background_image.max' => 'File exceeds the 5MB size limit. Maximum size is 5MB.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('background_image'),
                ], 422);
            }

            $image = $request->file('background_image');
            
            // Log file details for debugging
            Log::info('Uploading image:', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType(),
                'extension' => $image->getClientOriginalExtension()
            ]);

            // Get image contents
            $imageContents = file_get_contents($image->getRealPath());
            
            if ($imageContents === false) {
                throw new \Exception('Failed to read image file');
            }
            
            // Optional: Resize and optimize image if it's too large
            // You'll need to install intervention/image package: composer require intervention/image
            /*
            if (class_exists(\Intervention\Image\ImageManager::class)) {
                $manager = new ImageManager(['driver' => 'gd']);
                $img = $manager->make($imageContents);
                
                // Resize if width > 1920px
                if ($img->width() > 1920) {
                    $img->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                
                // Compress quality if file is still large
                if ($image->getSize() > 2 * 1024 * 1024) { // If > 2MB
                    $img->encode(null, 75); // Compress to 75% quality
                }
                
                $imageContents = (string) $img->encode();
            }
            */
            
            $imageData = base64_encode($imageContents);

            Homepage::updateOrCreate(
                ['key' => 'hero_background'],
                ['image_data' => $imageData]
            );

            return response()->json([
                'success' => true,
                'message' => 'Background image uploaded successfully!',
            ]);

        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessage = 'Invalid file';

            if (isset($errors['background_image'])) {
                $errorMessage = $errors['background_image'][0];
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error uploading homepage image: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            // Provide more specific error messages based on the exception
            $errorMessage = 'Server error: Unable to process the image. ';
            
            if (str_contains($e->getMessage(), 'memory')) {
                $errorMessage .= 'The image is too large to process. Please try a smaller image (max 5MB).';
            } elseif (str_contains($e->getMessage(), 'corrupt') || str_contains($e->getMessage(), 'invalid')) {
                $errorMessage .= 'The image file appears to be corrupted. Please try a different image.';
            } else {
                $errorMessage .= 'Please try again with a different image.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
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

            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'No background image found to remove.',
                ], 404);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Background image removed successfully. Default picture will now be displayed.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing homepage image: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image. Please try again.',
            ], 500);
        }
    }
}