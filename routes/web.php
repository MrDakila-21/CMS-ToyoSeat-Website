<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin routes
Route::prefix('admin')->group(function () {
    // Login routes (accessible only for guests)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Protected admin routes (must be authenticated)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/check-auth', [AdminAuthController::class, 'checkAuth'])->name('admin.checkAuth');
        
        // Homepage image management routes (moved here from guest group)
        Route::get('/homepage/image', [App\Http\Controllers\Admin\HomepageController::class, 'getImage']);
        Route::post('/homepage/upload-image', [App\Http\Controllers\Admin\HomepageController::class, 'uploadImage']);
        Route::delete('/homepage/remove-image', [App\Http\Controllers\Admin\HomepageController::class, 'removeImage']);
    });
});

// Fallback route for any unauthorized access
Route::fallback(function () {
    if (!auth()->check()) {
        return redirect('/admin/login');
    }
    return redirect('/');
});