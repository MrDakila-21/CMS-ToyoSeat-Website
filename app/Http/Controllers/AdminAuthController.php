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
    $validTabs = ['home', 'about', 'recruitment', 'news', 'inquiry'];
    $validAboutSubtabs = ['overview', 'business', 'location', 'history', 'iso', 'privacy'];
    $validNewsSubtabs = ['media', 'announcements'];
    
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
    
    // Prepare data for views that need it
    $events = null;
    $announcements = null;
    $content = null;
    $automotive = null;
    $organizations = null;
    $characteristics = null;
    $partnerships = null;
    
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
        $announcements = []; // Add your announcements data here
    }
    
    // Return the appropriate view directly (no AJAX)
    $viewPath = "admin.partials.{$tab}";
    
    if ($tab === 'about') {
        $viewPath = "admin.partials.about.{$subtab}";
    } elseif ($tab === 'news') {
        $viewPath = "admin.partials.news.{$subtab}";
    }
    
    // Check if view exists
    if (!view()->exists($viewPath)) {
        abort(404, "View not found: {$viewPath}");
    }
    
    return view('admin.dashboard', compact('tab', 'subtab', 'events', 'announcements', 'content', 'automotive', 'organizations', 'characteristics', 'partnerships'));
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
    
    // REMOVED: checkAuth() method - no longer needed for AJAX
    // REMOVED: loadContent() method - no longer needed for AJAX
}