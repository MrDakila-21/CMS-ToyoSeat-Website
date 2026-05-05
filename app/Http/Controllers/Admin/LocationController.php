<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        $location = CompanyLocation::getActiveLocation();
        return view('admin.partials.about.location', compact('location'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'google_maps_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:50|regex:/^[\+\-\s\(\)0-9]+$/',
            'email' => 'nullable|email|max:100',
            'working_hours' => 'nullable|string|max:1000'
        ], [
            'address_line1.required' => 'Address line 1 is required.',
            'city.required' => 'City is required.',
            'country.required' => 'Country is required.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'phone.regex' => 'Please enter a valid phone number.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Deactivate current active location
        CompanyLocation::where('is_active', true)->update(['is_active' => false]);
        
        // Create new location
        $validated = $validator->validated();
        $validated['is_active'] = true;
        $location = CompanyLocation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location saved successfully!',
            'location' => $location
        ]);
    }

    public function update(Request $request, $id)
    {
        $location = CompanyLocation::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'google_maps_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:50|regex:/^[\+\-\s\(\)0-9]+$/',
            'email' => 'nullable|email|max:100',
            'working_hours' => 'nullable|string|max:1000'
        ], [
            'address_line1.required' => 'Address line 1 is required.',
            'city.required' => 'City is required.',
            'country.required' => 'Country is required.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'phone.regex' => 'Please enter a valid phone number.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $location->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully!',
            'location' => $location
        ]);
    }

    public function getLocation()
    {
        $location = CompanyLocation::getActiveLocation();
        return response()->json(['location' => $location]);
    }

    public function destroy($id)
    {
        $location = CompanyLocation::findOrFail($id);
        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully!'
        ]);
    }
}