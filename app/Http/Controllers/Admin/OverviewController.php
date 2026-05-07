<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OverviewContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OverviewController extends Controller
{
    public function index()
    {
        $content = OverviewContent::getContent();
        return view('admin.partials.about.overview', compact('content'));
    }
    
    public function update(Request $request)
    {
        $content = OverviewContent::getContent();
        
        $validated = $request->validate([
            'business_principles' => 'nullable|array',
            'president_message' => 'nullable|string',
            'president_name' => 'nullable|string|max:255',
            'president_title' => 'nullable|string|max:255',
            'company_profile' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'established_date' => 'nullable|string|max:255',
            'capital' => 'nullable|string|max:255',
            'president_representative' => 'nullable|string|max:255',
            'business_description' => 'nullable|string',
            'employees' => 'nullable|integer',
            'president_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('president_image')) {
            if ($content->president_image && Storage::disk('public')->exists($content->president_image)) {
                Storage::disk('public')->delete($content->president_image);
            }
            $path = $request->file('president_image')->store('overview/president', 'public');
            $validated['president_image'] = $path;
        }
        
        if ($request->hasFile('company_profile_image')) {
            if ($content->company_profile_image && Storage::disk('public')->exists($content->company_profile_image)) {
                Storage::disk('public')->delete($content->company_profile_image);
            }
            $path = $request->file('company_profile_image')->store('overview/company', 'public');
            $validated['company_profile_image'] = $path;
        }
        
        $content->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Overview content updated successfully!'
        ]);
    }
    
    public function updateSection(Request $request)
    {
        \Log::info('Update section request received', $request->all());
        
        $content = OverviewContent::getContent();
        $section = $request->input('section');
        
        if ($section === 'president') {
            $validated = $request->validate([
                'president_name' => 'nullable|string|max:255',
                'president_title' => 'nullable|string|max:255',
                'president_message' => 'nullable|string',
                'president_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);
            
            if ($request->hasFile('president_image')) {
                if ($content->president_image && Storage::disk('public')->exists($content->president_image)) {
                    Storage::disk('public')->delete($content->president_image);
                }
                $path = $request->file('president_image')->store('overview/president', 'public');
                $validated['president_image'] = $path;
            }
            
            $content->update($validated);
            
            $content = OverviewContent::getContent();
            
            return response()->json([
                'success' => true,
                'message' => 'President section updated successfully!',
                'reload' => true, 
                'data' => [
                    'president_image' => $content->president_image ? '/storage.php?file=' . $content->president_image : null,
                    'president_name' => $content->president_name,
                    'president_title' => $content->president_title,
                    'president_message' => $content->president_message,
                ]
            ]);
            
        } elseif ($section === 'company') {
            $content = OverviewContent::getContent();
            
            if ($request->has('company_name')) {
                $content->company_name = $request->input('company_name');
            }
            if ($request->has('company_profile')) {
                $content->company_profile = $request->input('company_profile');
            }
            if ($request->has('established_date')) {
                $content->established_date = $request->input('established_date');
            }
            if ($request->has('capital')) {
                $content->capital = $request->input('capital');
            }
            if ($request->has('president_representative')) {
                $content->president_representative = $request->input('president_representative');
            }
            if ($request->has('business_description')) {
                $content->business_description = $request->input('business_description');
            }
            if ($request->has('employees')) {
                $content->employees = $request->input('employees');
            }
            
            $dynamicCategories = $content->dynamic_categories ?? [];
            
            $removedCategories = $request->input('removed_categories', []);
            if (is_string($removedCategories)) {
                $removedCategories = json_decode($removedCategories, true);
            }
            
            if (is_array($removedCategories)) {
                foreach ($removedCategories as $removedKey) {
                    if (isset($dynamicCategories[$removedKey])) {
                        unset($dynamicCategories[$removedKey]);
                    }
                }
            }
            
            $standardCategories = [
                '_token', '_method', 'section', 'company_profile_image', 
                'company_name', 'company_profile', 'removed_categories',
                'established_date', 'capital', 'president_representative', 
                'business_description', 'employees'
            ];
            
            foreach ($request->keys() as $key) {
                if (in_array($key, $standardCategories)) {
                    continue;
                }
                
                $value = $request->input($key);
                
                if ($value === null || $value === '') {
                    continue;
                }
                
                $dynamicCategories[$key] = $value;
            }
            
            $content->dynamic_categories = $dynamicCategories;
            
            if ($request->hasFile('company_profile_image')) {
                if ($content->company_profile_image && Storage::disk('public')->exists($content->company_profile_image)) {
                    Storage::disk('public')->delete($content->company_profile_image);
                }
                $path = $request->file('company_profile_image')->store('overview/company', 'public');
                $content->company_profile_image = $path;
            }
            
            \Log::info('Saving company section with dynamic categories:', ['dynamic_categories' => $dynamicCategories]);
            
            $content->save();
            
            $content = OverviewContent::getContent();
            
            $dynamicCategoriesData = [];
            if ($content->dynamic_categories && is_array($content->dynamic_categories)) {
                foreach ($content->dynamic_categories as $key => $value) {
                    if (!in_array($key, ['established_date', 'capital', 'president_representative', 'business_description', 'employees'])) {
                        $dynamicCategoriesData[$key] = $value;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Company section updated successfully!',
                'reload' => true, 
                'data' => [
                    'company_profile_image' => $content->company_profile_image ? '/storage.php?file=' . $content->company_profile_image : null,
                    'company_name' => $content->company_name,
                    'company_profile' => $content->company_profile,
                    'established_date' => $content->established_date,
                    'capital' => $content->capital,
                    'president_representative' => $content->president_representative,
                    'business_description' => $content->business_description,
                    'employees' => $content->employees,
                    'dynamic_categories' => $dynamicCategoriesData,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid section specified'
        ]);
    }
    
    public function removeImage(Request $request)
    {
        $content = OverviewContent::getContent();
        $imageType = $request->input('image_type');
        
        if ($imageType === 'president' && $content->president_image) {
            if (Storage::disk('public')->exists($content->president_image)) {
                Storage::disk('public')->delete($content->president_image);
            }
            $content->president_image = null;
            $content->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'President image removed successfully'
            ]);
        }
        
        if ($imageType === 'company' && $content->company_profile_image) {
            if (Storage::disk('public')->exists($content->company_profile_image)) {
                Storage::disk('public')->delete($content->company_profile_image);
            }
            $content->company_profile_image = null;
            $content->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Company image removed successfully'
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Image not found'
        ]);
    }
    
    public function addBusinessPrinciple(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'required|string'
        ]);
        
        $content = OverviewContent::getContent();
        $principles = $content->business_principles ?? [];
        
        $newPrinciple = [
            'id' => uniqid(),
            'title' => $request->title ?? '',
            'description' => $request->description
        ];
        
        $principles[] = $newPrinciple;
        
        $content->business_principles = $principles;
        $content->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Business principle added successfully!',
            'principle' => $newPrinciple
        ]);
    }
    
    public function updateBusinessPrinciple(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'required|string'
        ]);
        
        $content = OverviewContent::getContent();
        $principles = $content->business_principles ?? [];
        $updatedPrinciple = null;
        
        foreach ($principles as $key => $principle) {
            if ($principle['id'] == $id) {
                $principles[$key]['title'] = $request->title ?? '';
                $principles[$key]['description'] = $request->description;
                $updatedPrinciple = $principles[$key];
                break;
            }
        }
        
        $content->business_principles = $principles;
        $content->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Business principle updated successfully!',
            'principle' => $updatedPrinciple
        ]);
    }

    public function addCategory(Request $request)
    {
        \Log::info('Add category request received', $request->all());
        
        $request->validate([
            'category_key' => 'required|string|regex:/^[a-z_]+$/|max:255',
            'category_label' => 'required|string|max:255',
            'category_icon' => 'nullable|string|max:255',
            'field_type' => 'required|in:text,textarea,number',
            'initial_value' => 'nullable|string'
        ]);
        
        $content = OverviewContent::getContent();
        $dynamicCategories = $content->dynamic_categories ?? [];
        
        $categoryKey = $request->input('category_key');
        
        if (isset($dynamicCategories[$categoryKey])) {
            return response()->json([
                'success' => false,
                'message' => 'Category with this key already exists'
            ]);
        }
        
        $dynamicCategories[$categoryKey] = $request->input('initial_value');
        $content->dynamic_categories = $dynamicCategories;
        
        $categoryMetadata = $content->category_metadata ?? [];
        $categoryMetadata[$categoryKey] = [
            'label' => $request->input('category_label'),
            'icon' => $request->input('category_icon', 'fa-tag'),
            'field_type' => $request->input('field_type')
        ];
        $content->category_metadata = $categoryMetadata;
        
        $content->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!'
        ]);
    }
    
    public function deleteBusinessPrinciple($id)
    {
        $content = OverviewContent::getContent();
        $principles = $content->business_principles ?? [];
        
        $principles = array_filter($principles, function($principle) use ($id) {
            return $principle['id'] != $id;
        });
        
        $content->business_principles = array_values($principles);
        $content->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Business principle deleted successfully!'
        ]);
    }
}