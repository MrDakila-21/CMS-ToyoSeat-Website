<?php

namespace App\Http\Controllers;

use App\Models\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OverviewContent;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'string',
            'password' => 'required',
        ]);

        // Custom authentication without bcrypt
        $user = \App\Models\User::where('name', $credentials['name'])->first();
        
        // Check if user exists and password matches
        if ($user && $user->password === $credentials['password']) {
            // Check if account is active
            if (!$user->is_active) {
                return back()->withErrors([
                    'name' => 'Your account has been deactivated. Please contact the administrator.',
                ])->onlyInput('name');
            }
            
            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->regenerateToken();
            
            return redirect()->intended('/admin/dashboard')->with('success', 'Welcome ' . ($user->display_name ?? $user->name) . '! You have successfully logged in.');
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ])->onlyInput('name');
    }

    public function dashboard(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        // Get tab from query parameters
        $tab = $request->query('tab', 'home');
        $subtab = $request->query('subtab');
        
        // Validate that the tab/subtab combination exists
        $validTabs = ['home', 'about', 'recruitment', 'news', 'inquiry', 'users'];
        $validAboutSubtabs = ['overview', 'business', 'location', 'history', 'iso', 'privacy'];
        $validNewsSubtabs = ['media', 'announcements'];
        $validUsersSubtabs = ['list'];
        
        if (!in_array($tab, $validTabs)) {
            $tab = 'home';
            $subtab = null;
        }
        
        if ($tab === 'about' && !in_array($subtab, $validAboutSubtabs)) {
            $subtab = 'overview';
        }
        
        if ($tab === 'news' && !in_array($subtab, $validNewsSubtabs)) {
            $subtab = 'media';
        }
        
        if ($tab === 'users' && !in_array($subtab, $validUsersSubtabs)) {
            $subtab = 'list';
        }
        
        // Prepare data for views that need it
        $events = null;
        $announcements = null;
        $content = null;
        $automotive = null;
        $organizations = null;
        $characteristics = null;
        $partnerships = null;
        $users = null;
        
        if ($tab === 'about' && $subtab === 'overview') {
            $content = \App\Models\OverviewContent::getContent();
        }
        
        // Add this for business subtab
        if ($tab === 'about' && $subtab === 'business') {
            $automotive = \App\Models\BusinessContent::getAutomotiveSeats();
            $organizations = \App\Models\BusinessContent::getOrganizationMembers();
            $characteristics = \App\Models\BusinessContent::getCharacteristics();
            $partnerships = \App\Models\BusinessContent::getPartnerships();
        }
        
        if ($tab === 'news' && $subtab === 'media') {
            try {
                $events = EventActivity::orderBy('created_at', 'desc')->get();
            } catch (\Exception $e) {
                $events = collect([]);
            }
        }
        
        if ($tab === 'news' && $subtab === 'announcements') {
            $announcements = [];
        }
        
        // Add users data for superadmin only
        if ($tab === 'users') {
            if (Auth::user()->account_type !== 'superadmin') {
                abort(403, 'Access denied. Only Super Administrators can access user management.');
            }
            $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(10);
        }
        
        // Return the appropriate view directly
        $viewPath = "admin.partials.{$tab}";
        
        if ($tab === 'about') {
            $viewPath = "admin.partials.about.{$subtab}";
        } elseif ($tab === 'news') {
            $viewPath = "admin.partials.news.{$subtab}";
        } elseif ($tab === 'users') {
            $viewPath = "admin.partials.users.{$subtab}";
        }
        
        // Check if view exists
        if (!view()->exists($viewPath)) {
            abort(404, "View not found: {$viewPath}");
        }
        
        return view('admin.dashboard', compact('tab', 'subtab', 'events', 'announcements', 'content', 'automotive', 'organizations', 'characteristics', 'partnerships', 'users'));
    }

    public function logout(Request $request)
    {
        // Clear all session data
        Auth::logout();
        
        // Invalidate the session completely
        $request->session()->invalidate();
        
        // Regenerate the CSRF token
        $request->session()->regenerateToken();
        
        // Clear all session data explicitly
        $request->session()->flush();
        
        // Clear any remember me cookies
        if ($request->hasCookie(Auth::getRecallerName())) {
            $cookie = \Cookie::forget(Auth::getRecallerName());
            return redirect('/admin/login')->with('success', 'Successfully logged out!')->withCookie($cookie);
        }
        
        return redirect('/admin/login')->with('success', 'Successfully logged out!');
    }
}