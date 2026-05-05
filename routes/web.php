<?php

use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\EventActivityController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Guest\EventActivityController as GuestEventActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Guest\LocationController as GuestLocationController;

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
        Route::get('/location', [GuestLocationController::class, 'index'])->name('location');
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
        Route::get('/media-information', [GuestEventActivityController::class, 'index'])->name('media-information');
        Route::view('/announcements', 'guest.news.announcements')->name('announcements');
    });
    
    // Inquiry page
    Route::prefix('inquiry')->name('inquiry.')->group(function () {
        Route::view('/', 'guest.inquiry.inquiry')->name('index');
        Route::post('/send', [InquiryController::class, 'store'])->name('store');
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
        

        // IMPORTANT: Make sure these routes are correctly defined
        // Homepage slideshow management routes
        Route::get('/homepage/slides', [HomepageController::class, 'getSlides'])->name('admin.homepage.slides');
        Route::post('/homepage/upload-multiple', [HomepageController::class, 'uploadMultipleImages'])->name('admin.homepage.uploadMultiple');
        Route::post('/homepage/update-order', [HomepageController::class, 'updateSlidesOrder'])->name('admin.homepage.updateOrder');
        Route::post('/homepage/present', [HomepageController::class, 'presentSlides'])->name('admin.homepage.present');
        Route::delete('/homepage/slide/{id}', [HomepageController::class, 'deleteSlide'])->name('admin.homepage.deleteSlide');
        
        // Legacy route (keep for compatibility)
        Route::get('/homepage/image', [HomepageController::class, 'getImage'])->name('admin.homepage.image');
        Route::post('/homepage/upload-image', [HomepageController::class, 'uploadImage'])->name('admin.homepage.upload');
        Route::delete('/homepage/remove-image', [HomepageController::class, 'removeImage'])->name('admin.homepage.remove');

        // Event/Activity management routes
        Route::get('media/all', [EventActivityController::class, 'getAll'])->name('admin.media.all');
        Route::resource('media', EventActivityController::class)->names('admin.media');
        Route::patch('media/{id}/status/{status}', [EventActivityController::class, 'updateStatus'])->name('media.status');

        Route::post('/overview/add-category', [OverviewController::class, 'addCategory'])->name('admin.overview.addCategory');
        
        // Overview management routes
        Route::get('/overview', [OverviewController::class, 'index'])->name('admin.overview');
        Route::post('/overview/update', [OverviewController::class, 'update'])->name('admin.overview.update');
        Route::post('/overview/business-principle/add', [OverviewController::class, 'addBusinessPrinciple'])->name('admin.overview.addPrinciple');
        Route::put('/overview/business-principle/{id}', [OverviewController::class, 'updateBusinessPrinciple'])->name('admin.overview.updatePrinciple');
        Route::delete('/overview/business-principle/{id}', [OverviewController::class, 'deleteBusinessPrinciple'])->name('admin.overview.deletePrinciple');
        // Add these routes inside the admin middleware group
        Route::post('/overview/update-section', [OverviewController::class, 'updateSection'])->name('admin.overview.updateSection');
        Route::post('/overview/remove-image', [OverviewController::class, 'removeImage'])->name('admin.overview.removeImage');

        Route::prefix('location')->name('admin.location.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LocationController::class, 'index'])->name('index');
            Route::post('/store', [\App\Http\Controllers\Admin\LocationController::class, 'store'])->name('store');
            Route::put('/update/{id}', [\App\Http\Controllers\Admin\LocationController::class, 'update'])->name('update');
            Route::get('/get', [\App\Http\Controllers\Admin\LocationController::class, 'getLocation'])->name('get');
            Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\LocationController::class, 'destroy'])->name('delete');
        });
        
        // NEW: Additional routes for folder image management
        Route::post('media/upload-direct', [EventActivityController::class, 'uploadDirectImage'])->name('admin.media.uploadDirect');
        Route::post('media/sync-images', [EventActivityController::class, 'syncAllImages'])->name('admin.media.syncImages');
        Route::post('media/batch-upload', [EventActivityController::class, 'batchUploadToFolder'])->name('admin.media.batchUpload');

        // Add a test route to verify JSON responses work
        Route::get('/test-json', function() {
            return response()->json(['success' => true, 'message' => 'JSON test successful']);
        });
    });
});

// Fallback route for any undefined admin routes
Route::fallback(function () {
    if (request()->is('admin/*') && !Auth::check()) {
        return redirect()->route('admin.login');
    }
});

