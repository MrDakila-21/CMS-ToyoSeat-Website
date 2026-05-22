<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\EventActivityController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Guest\AnnouncementController as GuestAnnouncementController;
use App\Http\Controllers\Guest\EventActivityController as GuestEventActivityController;
use App\Http\Controllers\Guest\IsoObtainedController;
use App\Http\Controllers\Guest\LocationController as GuestLocationController;
use App\Http\Controllers\Guest\HistoryController as GuestHistoryController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusinessContentController;
use App\Http\Controllers\Guest\BusinessIntroductionController;
use App\Http\Controllers\Admin\RecruitmentController; 
use App\Http\Controllers\Admin\HistoryController; 
use App\Http\Controllers\Admin\UserController; // ADD THIS LINE

// Image serving route
Route::get('/image/{path}', [ImageController::class, 'serve'])
    ->where('path', '.*')
    ->name('image.serve');

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
        Route::get('/business-introduction', [BusinessIntroductionController::class, 'index'])->name('business-introduction');
        Route::get('/location', [GuestLocationController::class, 'index'])->name('location');
        Route::get('/history', [GuestHistoryController::class, 'index'])->name('history');
        Route::get('/history-data', [GuestHistoryController::class, 'getPublished'])->name('history-data');
        Route::get('/iso-obtained', [IsoObtainedController::class, 'index'])->name('iso-obtained');
        Route::view('/privacy-policy', 'guest.about.privacy-policy')->name('privacy-policy');
    });

    // Recruitment pages
    Route::prefix('recruitment')->name('recruitment.')->group(function () {
        Route::get('/information', [\App\Http\Controllers\Guest\RecruitmentController::class, 'index'])->name('information');
        Route::get('/api/published', [\App\Http\Controllers\Guest\RecruitmentController::class, 'getPublished'])->name('api.published');
    });

    // News pages
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/media-information', [GuestEventActivityController::class, 'index'])->name('media-information');
        Route::get('/announcements', [GuestAnnouncementController::class, 'index'])->name('announcements');
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
        // In the admin routes group, after the media routes, add:
        Route::delete('media/{id}/remove-image', [EventActivityController::class, 'removeImage'])->name('admin.media.removeImage');

        // Announcements management routes
        Route::get('announcements/all', [AnnouncementController::class, 'getAll'])->name('admin.announcements.all');
        Route::resource('announcements', AnnouncementController::class)->names('admin.announcements');
        Route::patch('announcements/{id}/status/{status}', [AnnouncementController::class, 'updateStatus'])->name('admin.announcements.updateStatus');

        // Additional routes for folder image management
        Route::post('announcements/upload-direct', [AnnouncementController::class, 'uploadDirectImage'])->name('admin.announcements.uploadDirect');
        Route::post('announcements/sync-images', [AnnouncementController::class, 'syncAllImages'])->name('admin.announcements.syncImages');
        Route::post('announcements/batch-upload', [AnnouncementController::class, 'batchUploadToFolder'])->name('admin.announcements.batchUpload');

        // History management routes
        Route::get('histories/all', [App\Http\Controllers\Admin\HistoryController::class, 'getAll'])->name('admin.histories.all');
        Route::resource('histories', App\Http\Controllers\Admin\HistoryController::class)->names('admin.histories');
        Route::patch('histories/{id}/status/{status}', [App\Http\Controllers\Admin\HistoryController::class, 'updateStatus'])->name('admin.histories.updateStatus');
        Route::post('histories/upload-direct', [App\Http\Controllers\Admin\HistoryController::class, 'uploadDirectImage'])->name('admin.histories.uploadDirect');
        Route::delete('histories/{id}/remove-image', [App\Http\Controllers\Admin\HistoryController::class, 'removeImage'])->name('admin.histories.removeImage');
        // Recruitment management routes
        Route::get('recruitments/all', [RecruitmentController::class, 'getAll'])->name('admin.recruitments.all');
        Route::resource('recruitments', RecruitmentController::class)->names('admin.recruitments');
        Route::patch('recruitments/{id}/status/{status}', [RecruitmentController::class, 'updateStatus'])->name('admin.recruitments.updateStatus');

        Route::post('/overview/add-category', [OverviewController::class, 'addCategory'])->name('admin.overview.addCategory');

        // Overview management routes
        Route::get('/overview', [OverviewController::class, 'index'])->name('admin.overview');
        Route::post('/overview/update', [OverviewController::class, 'update'])->name('admin.overview.update');
        Route::post('/overview/business-principle/add', [OverviewController::class, 'addBusinessPrinciple'])->name('admin.overview.addPrinciple');
        Route::put('/overview/business-principle/{id}', [OverviewController::class, 'updateBusinessPrinciple'])->name('admin.overview.updatePrinciple');
        Route::delete('/overview/business-principle/{id}', [OverviewController::class, 'deleteBusinessPrinciple'])->name('admin.overview.deletePrinciple');
        
        Route::post('/overview/update-section', [OverviewController::class, 'updateSection'])->name('admin.overview.updateSection');
        Route::post('/overview/remove-image', [OverviewController::class, 'removeImage'])->name('admin.overview.removeImage');

        Route::prefix('location')->name('admin.location.')->group(function () {
            Route::get('/', [LocationController::class, 'index'])->name('index');
            Route::post('/store', [LocationController::class, 'store'])->name('store');
            Route::put('/update/{id}', [LocationController::class, 'update'])->name('update');
            Route::get('/get', [LocationController::class, 'getLocation'])->name('get');
            Route::delete('/delete/{id}', [LocationController::class, 'destroy'])->name('delete');
        });

        // Additional routes for folder image management
        Route::post('media/upload-direct', [EventActivityController::class, 'uploadDirectImage'])->name('admin.media.uploadDirect');
        Route::post('media/sync-images', [EventActivityController::class, 'syncAllImages'])->name('admin.media.syncImages');
        Route::post('media/batch-upload', [EventActivityController::class, 'batchUploadToFolder'])->name('admin.media.batchUpload');

        // ISO Obtained management routes - Full CRUD support
        Route::get('/iso-obtained', [App\Http\Controllers\Admin\IsoObtainedController::class, 'index'])->name('admin.iso.index');
        Route::post('/iso-obtained', [App\Http\Controllers\Admin\IsoObtainedController::class, 'store'])->name('admin.iso.store');
        Route::get('/iso-obtained/{id}/edit', [App\Http\Controllers\Admin\IsoObtainedController::class, 'edit'])->name('admin.iso.edit');
        Route::put('/iso-obtained/{id}', [App\Http\Controllers\Admin\IsoObtainedController::class, 'update'])->name('admin.iso.update');
        Route::patch('/iso-obtained/{id}/status/{status}', [App\Http\Controllers\Admin\IsoObtainedController::class, 'updateStatus'])->name('admin.iso.status');
        Route::delete('/iso-obtained/{id}', [App\Http\Controllers\Admin\IsoObtainedController::class, 'destroy'])->name('admin.iso.destroy');
        Route::delete('/iso-obtained/{id}/remove-image', [App\Http\Controllers\Admin\IsoObtainedController::class, 'removeImage'])->name('admin.iso.removeImage');
        
        // Business content routes
        Route::prefix('business-content')->name('admin.business.')->group(function () {
            Route::get('/', [BusinessContentController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [BusinessContentController::class, 'edit'])->name('edit');
            
            // Automotive routes
            Route::post('/automotive', [BusinessContentController::class, 'storeAutomotive'])->name('automotive.store');
            Route::put('/automotive/{id}', [BusinessContentController::class, 'updateAutomotive'])->name('automotive.update');
            
            // Organization routes
            Route::post('/organization', [BusinessContentController::class, 'storeOrganization'])->name('organization.store');
            Route::put('/organization/{id}', [BusinessContentController::class, 'updateOrganization'])->name('organization.update');
            
            // Characteristic routes
            Route::post('/characteristic', [BusinessContentController::class, 'storeCharacteristic'])->name('characteristic.store');
            Route::put('/characteristic/{id}', [BusinessContentController::class, 'updateCharacteristic'])->name('characteristic.update');
            
            // Partnership routes
            Route::post('/partnership', [BusinessContentController::class, 'storePartnership'])->name('partnership.store');
            Route::put('/partnership/{id}', [BusinessContentController::class, 'updatePartnership'])->name('partnership.update');
            
            // Delete and order routes
            Route::delete('/{id}', [BusinessContentController::class, 'destroy'])->name('destroy');
            Route::post('/update-order', [BusinessContentController::class, 'updateOrder'])->name('update-order');
        });

       // User Management routes
        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/filter', [UserController::class, 'filter'])->name('filter'); // ADD THIS LINE
            Route::get('/all', [UserController::class, 'getAll'])->name('all');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

            // Settings routes (accessible to all authenticated users)
            Route::prefix('settings')->name('admin.settings.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
                Route::post('/update', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
            });

        // Add a test route to verify JSON responses work
        Route::get('/test-json', function () {
            return response()->json(['success' => true, 'message' => 'JSON test successful']);
        });
    });
});

// Fallback route for any undefined admin routes
Route::fallback(function () {
    if (request()->is('admin/*') && ! Auth::check()) {
        return redirect()->route('admin.login');
    }
});