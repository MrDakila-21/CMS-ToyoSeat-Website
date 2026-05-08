<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckActiveSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $currentSessionId = $request->session()->getId();
            $userId = Auth::id();
            
            // Check if this session is still the active one for this user
            $activeSession = DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', $currentSessionId)
                ->exists();
            
            if (!$activeSession) {
                // This session has been invalidated (user logged in elsewhere)
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('admin.login')
                    ->withErrors(['name' => 'You have been logged out because another session was started.']);
            }
        }
        
        return $next($request);
    }
}