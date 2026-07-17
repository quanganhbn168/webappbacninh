<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AdBannerController;
use App\Http\Controllers\Admin\MiniAppController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\TemplateCategoryController;
use App\Http\Controllers\Admin\SlugController as AdminSlugController;
use App\Http\Controllers\Admin\BulkActionController;

// Redirect legacy /admin to secret prefix
Route::get('/admin/{path?}', function ($path = null) {
    return redirect('/system029/admin/' . ($path ? $path : 'login'));
})->where('path', '.*');

// Landlord Admin Authentication (Obfuscated)
Route::prefix('system029/admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Landlord Admin Panel (Obfuscated)
Route::prefix('system029/admin')->middleware(['web', 'auth:admin', 'landlord'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Global Slug Check
    Route::get('/check-slug', [AdminSlugController::class, 'check'])->name('admin.check-slug');

    // Global Bulk Destroy
    Route::post('/bulk-destroy', [BulkActionController::class, 'destroy'])->name('admin.bulk-destroy');

    // Global Duplicate
    Route::post('/global/duplicate', [App\Http\Controllers\Admin\DuplicateController::class, 'store'])->name('admin.global.duplicate');

    // Tenants
    Route::resource('tenants', TenantController::class)->names('admin.tenants');
    
    // Mini Apps
    Route::post('mini-apps/update-order', [MiniAppController::class, 'updateOrder'])->name('admin.mini-apps.updateOrder');
    Route::resource('mini-apps', MiniAppController::class)->names('admin.mini-apps');

    // Blog
    Route::post('blog/upload-image', [PostController::class, 'uploadImage'])->name('admin.blog.upload');
    Route::resource('blog', PostController::class)->names('admin.blog');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/test-mail', [SettingController::class, 'sendTestMail'])->name('admin.settings.test-mail');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Users
    Route::resource('users', UserController::class)->names('admin.users');

    // Projects
    Route::post('projects/update-order', [ProjectController::class, 'updateOrder'])->name('admin.projects.updateOrder');
    Route::resource('projects', ProjectController::class)->names('admin.projects');

    // Ad Banners
    Route::post('ad-banners/update-order', [AdBannerController::class, 'updateOrder'])->name('admin.ad-banners.updateOrder');
    Route::resource('ad-banners', AdBannerController::class)->names('admin.ad-banners');

    // Services
    Route::resource('service-categories', ServiceCategoryController::class)->names('admin.service-categories');
    Route::post('services/update-order', [ServiceController::class, 'updateOrder'])->name('admin.services.updateOrder');
    Route::resource('services', ServiceController::class)->names('admin.services');

    // Template Categories
    Route::resource('template-categories', TemplateCategoryController::class)->names('admin.template-categories');

    // Templates
    Route::post('templates/update-order', [AdminTemplateController::class, 'updateOrder'])->name('admin.templates.updateOrder');
    Route::resource('templates', AdminTemplateController::class)->names('admin.templates');

    // Laravel File Manager
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});
