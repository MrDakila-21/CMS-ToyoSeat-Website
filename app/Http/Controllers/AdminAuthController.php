<?php

namespace App\Http\Controllers;

use App\Models\EventActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Regenerate session ID for security
            $request->session()->regenerateToken();
            
            // Redirect with success message
            return redirect()->intended('/admin/dashboard')->with('success', 'Welcome ADMIN! You have successfully logged in.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        // Pass initial content data to the dashboard (supports deep-linking)
        $initialTab = request()->query('tab', 'home');
        $initialSubtab = request()->query('subtab');

        return view('admin.dashboard', compact('initialTab', 'initialSubtab'));
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
    
    // Add this method to check authentication status via AJAX
    public function checkAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check()
        ])->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',
        ]);
    }
    
    // New method to load content via AJAX
    public function loadContent(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $tab = $request->get('tab');
        $subtab = $request->get('subtab');
        
        $view = '';
        
        // Determine which view to load
        if ($tab === 'home') {
            $view = 'admin.partials.home';
        } elseif ($tab === 'about') {
            $view = "admin.partials.about.{$subtab}";
        } elseif ($tab === 'recruitment') {
            $view = 'admin.partials.recruitment';
        } elseif ($tab === 'news') {
            $view = "admin.partials.news.{$subtab}";
        } elseif ($tab === 'inquiry') {
            $view = 'admin.partials.inquiry';
        } else {
            return response()->json(['error' => 'Invalid tab'], 400);
        }
        
        // Check if view exists
        if (!view()->exists($view)) {
            return response()->json(['error' => 'Content not found'], 404);
        }

        // Provide view data for tabs that require it
        $viewData = [];
        if ($tab === 'news' && $subtab === 'media') {
            $viewData['events'] = EventActivity::orderBy('created_at', 'desc')->get();
        }

        $html = view($view, $viewData)->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}