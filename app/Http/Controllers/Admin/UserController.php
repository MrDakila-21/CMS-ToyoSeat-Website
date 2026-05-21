<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Check if current user is superadmin
     */
    private function isSuperAdmin()
    {
        $user = Auth::user();
        return $user && $user->account_type === 'superadmin';
    }

    /**
     * Display user management page
     */
    public function index(Request $request)
    {
        // Only superadmin can access
        if (!$this->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Only Super Administrators can manage users.');
        }

        $tab = $request->query('tab', 'users');
        $subtab = $request->query('subtab', 'list');
        
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.dashboard', compact('tab', 'subtab', 'users'));
    }

    /**
     * Filter users based on status and type
     */
    public function filter(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $status = $request->query('status', 'all');
        $type = $request->query('type', 'all');
        
        $query = User::query();
        
        // Apply status filter
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }
        
        // Apply type filter
        if ($type === 'admin') {
            $query->where('account_type', 'admin');
        } elseif ($type === 'superadmin') {
            $query->where('account_type', 'superadmin');
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->ajax()) {
            $html = view('admin.partials.users.user_rows', compact('users'))->render();
            $pagination = view('admin.partials.users.pagination', ['users' => $users])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'users' => $users
            ]);
        }
        
        return redirect()->route('admin.dashboard', ['tab' => 'users', 'subtab' => 'list']);
    }

    /**
     * Get all users for AJAX
     */
    public function getAll(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        }
        
        return redirect()->route('admin.dashboard', ['tab' => 'users']);
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name',
            'display_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'account_type' => 'required|in:admin,superadmin',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'email' => $request->name . '@temp.com',
                'password' => $request->password,
                'account_type' => $request->account_type,
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user for editing
     */
    public function edit($id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        // Prevent editing own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit your own account through this interface'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Prevent editing own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit your own account'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'display_name' => 'required|string|max:255',
            'account_type' => 'required|in:admin,superadmin',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->display_name = $request->display_name;
            $user->account_type = $request->account_type;
            $user->is_active = $request->is_active;
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 403);
        }

        try {
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Prevent toggling own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change your own account status'
            ], 403);
        }

        try {
            $user->is_active = !$user->is_active;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status'
            ], 500);
        }
    }
}