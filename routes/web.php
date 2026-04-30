<?php
// routes/web.php

use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Add this at the beginning of your routes file
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes
Route::prefix('guest')->name('guest.')->group(function () {
    
    // About Us pages
    Route::prefix('about')->name('about.')->group(function () {
        Route::view('/overview', 'guest.about.overview')->name('overview');
        Route::view('/business-introduction', 'guest.about.business-introduction')->name('business-introduction');
        Route::view('/location', 'guest.about.location')->name('location');
        Route::view('/history', 'guest.about.history')->name('history');
        Route::view('/iso-obtained', 'guest.about.iso-obtained')->name('iso-obtained');
        Route::view('/privacy-policy', 'guest.about.privacy-policy')->name('privacy-policy');
    });
    
    // Recruitment pages
    Route::prefix('recruitment')->name('recruitment.')->group(function () {
        Route::view('/information', 'guest.recruitment.recruitment-information')->name('information');
        Route::view('/new-graduate', 'guest.recruitment.new-graduate')->name('new-graduate');
        Route::view('/career', 'guest.recruitment.career')->name('career');
    });
    
    // News pages
    Route::prefix('news')->name('news.')->group(function () {
        Route::view('/media-information', 'guest.news.media-information')->name('media-information');
        Route::view('/announcements', 'guest.news.announcements')->name('announcements');
    });
    
    // Inquiry page
    Route::prefix('inquiry')->name('inquiry.')->group(function () {
        Route::view('/', 'guest.inquiry.inquiry')->name('index');
    });
});

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
        Route::get('/load-content', [AdminAuthController::class, 'loadContent'])->name('admin.loadContent');

        // Homepage slideshow management routes
        Route::get('/homepage/slides', [HomepageController::class, 'getSlides'])->name('admin.homepage.slides');
        Route::post('/homepage/upload-multiple', [HomepageController::class, 'uploadMultipleImages'])->name('admin.homepage.uploadMultiple');
        Route::post('/homepage/update-order', [HomepageController::class, 'updateSlidesOrder'])->name('admin.homepage.updateOrder');
        Route::post('/homepage/present', [HomepageController::class, 'presentSlides'])->name('admin.homepage.present');
        Route::delete('/homepage/slide/{id}', [HomepageController::class, 'deleteSlide'])->name('admin.homepage.deleteSlide');
        
        // Legacy route (keep for compatibility)
        Route::get('/homepage/image', [HomepageController::class, 'getImage'])->name('admin.homepage.image');
    });
});

// Fallback route for any undefined admin routes
Route::fallback(function () {
    if (request()->is('admin/*') && !Auth::check()) {
        return redirect()->route('admin.login');
    }
});