<?php
// app/Http/Controllers/Admin/BusinessContentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessContentController extends Controller
{
    public function index()
    {
        $automotive = BusinessContent::getAutomotiveSeats();
        $organizations = BusinessContent::getOrganizationMembers();
        $characteristics = BusinessContent::getCharacteristics();
        $partnerships = BusinessContent::getPartnerships();
        
        return view('admin.partials.about.business', compact(
            'automotive', 'organizations', 'characteristics', 'partnerships'
        ));
    }
    
    // Automotive Section Methods
    public function storeAutomotive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        $data['section'] = 'automotive';
        $data['order'] = BusinessContent::where('section', 'automotive')->max('order') + 1;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('business/automotive', 'public');
            $data['image'] = $path;
        }
        
        $content = BusinessContent::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Automotive seat cover added successfully',
            'data' => $content
        ]);
    }
    
    public function updateAutomotive(Request $request, $id)
    {
        $content = BusinessContent::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $path = $request->file('image')->store('business/automotive', 'public');
            $data['image'] = $path;
        }
        
        $content->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Automotive seat cover updated successfully',
            'data' => $content
        ]);
    }
    
    // Organization Section Methods
    public function storeOrganization(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        $data['section'] = 'organization';
        $data['order'] = BusinessContent::where('section', 'organization')->max('order') + 1;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('business/organization', 'public');
            $data['image'] = $path;
        }
        
        $content = BusinessContent::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Organization member added successfully',
            'data' => $content
        ]);
    }
    
    public function updateOrganization(Request $request, $id)
    {
        $content = BusinessContent::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $path = $request->file('image')->store('business/organization', 'public');
            $data['image'] = $path;
        }
        
        $content->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Organization member updated successfully',
            'data' => $content
        ]);
    }
    
    // Characteristic Section Methods
    public function storeCharacteristic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        $data['section'] = 'characteristic';
        $data['order'] = BusinessContent::where('section', 'characteristic')->max('order') + 1;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('business/characteristics', 'public');
            $data['image'] = $path;
        }
        
        $content = BusinessContent::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Characteristic added successfully',
            'data' => $content
        ]);
    }
    
    public function updateCharacteristic(Request $request, $id)
    {
        $content = BusinessContent::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $path = $request->file('image')->store('business/characteristics', 'public');
            $data['image'] = $path;
        }
        
        $content->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Characteristic updated successfully',
            'data' => $content
        ]);
    }
    
    // Partnership Section Methods
    public function storePartnership(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        $data['section'] = 'partnership';
        $data['order'] = BusinessContent::where('section', 'partnership')->max('order') + 1;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('business/partnerships', 'public');
            $data['image'] = $path;
        }
        
        $content = BusinessContent::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Partnership added successfully',
            'data' => $content
        ]);
    }
    
    public function updatePartnership(Request $request, $id)
    {
        $content = BusinessContent::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $path = $request->file('image')->store('business/partnerships', 'public');
            $data['image'] = $path;
        }
        
        $content->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Partnership updated successfully',
            'data' => $content
        ]);
    }
    
    // Delete method for all sections
    public function destroy($id)
    {
        $content = BusinessContent::findOrFail($id);
        
        if ($content->image && Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }
        
        $content->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Content deleted successfully'
        ]);
    }

        // Add this method to BusinessContentController.php
    public function edit($id)
    {
        $content = BusinessContent::findOrFail($id);
        return response()->json($content);
    }
    
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'section' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        foreach ($request->items as $item) {
            BusinessContent::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully'
        ]);
    }
}