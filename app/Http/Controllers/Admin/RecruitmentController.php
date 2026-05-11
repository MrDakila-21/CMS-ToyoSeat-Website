<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    // Display all records
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'recruitment']);
    }

    // Get ALL records for AJAX
    public function getAll()
    {
        $recruitments = Recruitment::orderBy('created_at', 'desc')->get();
        return response()->json($recruitments);
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $recruitment = new Recruitment();
        $recruitment->title = $request->title;
        $recruitment->description = $request->description;
        $recruitment->status = 'published';
        $recruitment->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Recruitment post created successfully!', 'data' => $recruitment]);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'recruitment'])
            ->with('success', 'Recruitment post created successfully!');
    }

    // Get record for editing
    public function edit($id)
    {
        $recruitment = Recruitment::findOrFail($id);
        return response()->json($recruitment);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $recruitment = Recruitment::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $recruitment->title = $request->title;
        $recruitment->description = $request->description;
        $recruitment->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Recruitment post updated successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'recruitment'])
            ->with('success', 'Recruitment post updated successfully!');
    }

    // Delete record
    public function destroy($id)
    {
        $recruitment = Recruitment::findOrFail($id);
        $recruitment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Recruitment post deleted successfully!']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'recruitment'])
            ->with('success', 'Recruitment post deleted successfully!');
    }

    // Update status
    public function updateStatus($id, $status)
    {
        $recruitment = Recruitment::findOrFail($id);
        $recruitment->status = $status;
        $recruitment->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}