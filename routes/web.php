<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminAuthController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Us pages
Route::prefix('about')->group(function () {
    Route::view('/overview', 'about.overview')->name('about.overview');
    Route::view('/business-introduction', 'about.business-introduction')->name('about.business-introduction');
    Route::view('/location', 'about.location')->name('about.location');
    Route::view('/history', 'about.history')->name('about.history');
    Route::view('/iso-obtained', 'about.iso-obtained')->name('about.iso-obtained');
    Route::view('/privacy-policy', 'about.privacy-policy')->name('about.privacy-policy');
});

// Recruitment page
Route::view('/recruitment', 'recruitment.recruitment-information')->name('recruitment');

// News pages
Route::prefix('news')->group(function () {
    Route::view('/media-information', 'news.media-information')->name('news.media-information');
    Route::view('/announcements', 'news.announcements')->name('news.announcements');
});

// Inquiry page
Route::view('/inquiry', 'inquiry.inquiry')->name('inquiry');

// Admin routes
Route::prefix('admin')->group(function () {
    // Login routes (accessible only for guests)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Protected admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/check-auth', [AdminAuthController::class, 'checkAuth'])->name('admin.checkAuth');
    });

    // Fallback route for any unauthorized access
    Route::fallback(function () {
      if (!auth()->check()) {
          return redirect('/admin/login');
      }
       return redirect('/');
    });

});

