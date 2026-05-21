<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index(Request $request)
    {
        $tab = 'settings';
        $subtab = $request->query('subtab', 'profile');
        
        $user = Auth::user();
        
        return view('admin.dashboard', compact('tab', 'subtab', 'user'));
    }

    /**
     * Update user settings (flexible - only update what's provided)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $usernameChanged = false;
        $passwordChanged = false;
        $displayNameChanged = false;
        
        // Start with basic validation
        $rules = [
            'display_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,name,' . $user->id,
            'current_password' => 'required|string',
        ];
        
        // Add password validation only if new password is provided
        if ($request->filled('new_password')) {
            $rules['new_password'] = 'required|string|min:6';
            $rules['confirm_password'] = 'required|string|same:new_password';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if ($user->password !== $request->current_password) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Check what's being updated and validate no duplicate values
        $hasUpdates = false;
        
        // Check display name change
        if ($user->display_name !== $request->display_name) {
            // Check if new display name is same as old (case-insensitive)
            if (strtolower($user->display_name) === strtolower($request->display_name)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New display name is the same as your current display name'
                ], 422);
            }
            $displayNameChanged = true;
            $hasUpdates = true;
        }
        
        // Check username change
        if ($user->name !== $request->username) {
            // Check if new username is same as old (case-insensitive)
            if (strtolower($user->name) === strtolower($request->username)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New username is the same as your current username'
                ], 422);
            }
            $usernameChanged = true;
            $hasUpdates = true;
        }
        
        // Check password change
        if ($request->filled('new_password')) {
            // Check if new password is same as current
            if ($user->password === $request->new_password) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password cannot be the same as your current password'
                ], 422);
            }
            $passwordChanged = true;
            $hasUpdates = true;
        }
        
        // Only save if there are actual changes
        if (!$hasUpdates) {
            return response()->json([
                'success' => false,
                'message' => 'No changes were made to your account'
            ], 422);
        }
        
        try {
            // Apply changes
            if ($displayNameChanged) {
                $user->display_name = $request->display_name;
            }
            
            if ($usernameChanged) {
                $user->name = $request->username;
            }
            
            if ($passwordChanged) {
                $user->password = $request->new_password;
            }
            
            $user->save();

            // Build appropriate message
            $changes = [];
            if ($displayNameChanged) $changes[] = 'display name';
            if ($usernameChanged) $changes[] = 'username';
            if ($passwordChanged) $changes[] = 'password';
            
            $messageText = 'Your ' . implode(', ', $changes) . ' has been updated successfully!';
            
            if ($usernameChanged || $passwordChanged) {
                $messageText .= ' Please login again with your new credentials.';
            }

            return response()->json([
                'success' => true,
                'message' => $messageText,
                'user' => $user,
                'username_changed' => $usernameChanged,
                'password_changed' => $passwordChanged,
                'display_name_changed' => $displayNameChanged
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }
}