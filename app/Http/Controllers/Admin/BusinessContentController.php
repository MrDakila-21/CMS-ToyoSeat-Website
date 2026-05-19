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
            $file = $request->file('image');
            $path = $file->store('business/automotive', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content = BusinessContent::create($data);
        
        $html = view('admin.partials.about.business-components.automotive-item', ['item' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Automotive seat cover added successfully',
            'data' => $content,
            'html' => $html
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
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $file = $request->file('image');
            $path = $file->store('business/automotive', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content->update($data);
        
        $html = view('admin.partials.about.business-components.automotive-item', ['item' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Automotive seat cover updated successfully',
            'data' => $content,
            'html' => $html
        ]);
    }
    
    public function storeOrganization(Request $request)
{
    $validator = Validator::make($request->all(), [
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);
    
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    $data = $request->except('image');
    $data['section'] = 'organization';
    $data['order'] = BusinessContent::where('section', 'organization')->max('order') + 1;
    
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $path = $file->store('business/organization', 'public');
        $data['image'] = $path;
        $data['original_filename'] = $file->getClientOriginalName();
    }
    
    $content = BusinessContent::create($data);
    
    $html = view('admin.partials.about.business-components.organization-item', ['member' => $content])->render();
    
    return response()->json([
        'success' => true,
        'message' => 'Organization chart added successfully',
        'data' => $content,
        'html' => $html
    ]);
}

public function updateOrganization(Request $request, $id)
{
    $content = BusinessContent::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
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
        $file = $request->file('image');
        $path = $file->store('business/organization', 'public');
        $data['image'] = $path;
        $data['original_filename'] = $file->getClientOriginalName();
    }
    
    $content->update($data);
    
    $html = view('admin.partials.about.business-components.organization-item', ['member' => $content])->render();
    
    return response()->json([
        'success' => true,
        'message' => 'Organization chart updated successfully',
        'data' => $content,
        'html' => $html
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
            $file = $request->file('image');
            $path = $file->store('business/characteristics', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content = BusinessContent::create($data);
        
        $html = view('admin.partials.about.business-components.characteristic-item', ['char' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Characteristic added successfully',
            'data' => $content,
            'html' => $html
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
            $file = $request->file('image');
            $path = $file->store('business/characteristics', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content->update($data);
        
        $html = view('admin.partials.about.business-components.characteristic-item', ['char' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Characteristic updated successfully',
            'data' => $content,
            'html' => $html
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
            $file = $request->file('image');
            $path = $file->store('business/partnerships', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content = BusinessContent::create($data);
        
        $html = view('admin.partials.about.business-components.partnership-item', ['partner' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Partnership added successfully',
            'data' => $content,
            'html' => $html
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
            $file = $request->file('image');
            $path = $file->store('business/partnerships', 'public');
            $data['image'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }
        
        $content->update($data);
        
        $html = view('admin.partials.about.business-components.partnership-item', ['partner' => $content])->render();
        
        return response()->json([
            'success' => true,
            'message' => 'Partnership updated successfully',
            'data' => $content,
            'html' => $html
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
    
    public function edit($id)
    {
        $content = BusinessContent::findOrFail($id);
        
        $response = $content->toArray();
        $response['image_url'] = $content->image_url;
        $response['display_filename'] = $content->display_filename;
        
        return response()->json($response);
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