<?php

use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Protected admin routes (must be authenticated)
    Route::middleware(['auth', 'nocache'])->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/check-auth', [AdminAuthController::class, 'checkAuth'])->name('admin.checkAuth');
        Route::get('/load-content', [AdminAuthController::class, 'loadContent'])->name('admin.loadContent'); // Add this line

        // Homepage image management routes
        Route::get('/homepage/image', [HomepageController::class, 'getImage'])->name('admin.homepage.image');
        Route::post('/homepage/upload-image', [HomepageController::class, 'uploadImage'])->name('admin.homepage.upload');
        Route::delete('/homepage/remove-image', [HomepageController::class, 'removeImage'])->name('admin.homepage.remove');
    });
});

// Fallback route for any undefined admin routes
Route::fallback(function () {
    if (request()->is('admin/*') && !Auth::check()) {
        return redirect()->route('admin.login');
    }
});