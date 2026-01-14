<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\TenantRegisterController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AdBannerController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // Sitemap (Dynamic)
        Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

        // Trang chủ
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Tìm kiếm, đăng nhập, đăng ký (giữ nguyên)
        // Tìm kiếm
        Route::get('/search', fn() => view('frontend.index'))->name('search'); // Placeholder for search

        // Authentication Routes
        Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login']);
        Route::get('/register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register']);
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        // Social Login
        Route::get('/auth/{provider}', [CustomerAuthController::class, 'redirectToProvider'])->name('social.login');
        Route::get('/auth/{provider}/callback', [CustomerAuthController::class, 'handleProviderCallback']);

        Route::get('/contact', fn() => view('frontend.index'))->name('contact');

        // Blog
        Route::get('/blog', [\App\Http\Controllers\BlogPostController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [\App\Http\Controllers\BlogPostController::class, 'show'])->name('blog.show');

        // Check Domain
        Route::get('/domain-check', [DomainController::class, 'check'])->name('domain.check');
    });
}

// Template theo ngành (route cho danh mục)
Route::get('/templates', fn() => view('frontend.templates.index'))->name('templates.index');
Route::get('/templates/{slug}', function ($slug) {
    return view('frontend.templates.show', compact('slug'));
})->name('templates.show');

// Dịch vụ hosting, tên miền...
Route::get('/services/{slug}', function ($slug) {
    return view('frontend.services.show', compact('slug'));
})->name('services.show');
Route::post('/subscribe', function () {
    return back()->with('success', 'Cảm ơn bạn đã để lại email, chúng tôi sẽ liên hệ sớm!');
})->name('subscribe.email');



// Route để hiển thị trang công cụ lấy ảnh cover
Route::get('/anh-cover', [ThumbnailController::class, 'showCoverPage'])->name('cover.page');

// Các route xử lý logic (sẽ làm ở các bước sau)
Route::post('/get-info', [ThumbnailController::class, 'getInfo'])->name('cover.getInfo');
Route::post('/download-thumbnail', [ThumbnailController::class, 'download'])->name('cover.download');
Route::post('/download-video', [ThumbnailController::class, 'downloadVideo'])->name('cover.download.video');


// routes/web.php
Route::get('/bulk-anh-cover', [ThumbnailController::class, 'showBulkCoverPage'])->name('cover.bulk.page');
Route::post('/download-bulk', [ThumbnailController::class, 'downloadBulk'])->name('cover.download.bulk');

// Đăng ký Tenant
Route::post('/create-tenant', [TenantRegisterController::class, 'store']);

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
Route::prefix('system029/admin')->middleware(['auth:admin', 'landlord'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Tenants
    Route::resource('tenants', TenantController::class)->names('admin.tenants');
    
    // Mini Apps
    Route::post('mini-apps/update-order', [\App\Http\Controllers\Admin\MiniAppController::class, 'updateOrder'])->name('admin.mini-apps.update-order');
    Route::delete('mini-apps/bulk-destroy', [\App\Http\Controllers\Admin\MiniAppController::class, 'bulkDestroy'])->name('admin.mini-apps.bulk-destroy');
    Route::resource('mini-apps', \App\Http\Controllers\Admin\MiniAppController::class)->names('admin.mini-apps');

    // Blog
    Route::post('blog/upload-image', [PostController::class, 'uploadImage'])->name('admin.blog.upload');
    Route::get('blog/check-slug', [PostController::class, 'checkSlug'])->name('admin.blog.check-slug');
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
    Route::post('projects/bulk-destroy', [ProjectController::class, 'bulkDestroy'])->name('admin.projects.bulkDestroy');
    Route::post('projects/update-order', [ProjectController::class, 'updateOrder'])->name('admin.projects.updateOrder');
    Route::resource('projects', ProjectController::class)->names('admin.projects');

    // Ad Banners
    Route::post('ad-banners/bulk-destroy', [AdBannerController::class, 'bulkDestroy'])->name('admin.ad-banners.bulkDestroy');
    Route::post('ad-banners/update-order', [AdBannerController::class, 'updateOrder'])->name('admin.ad-banners.updateOrder');
    Route::resource('ad-banners', AdBannerController::class)->names('admin.ad-banners');

    // Services
    Route::post('services/bulk-destroy', [\App\Http\Controllers\Admin\ServiceController::class, 'bulkDestroy'])->name('admin.services.bulkDestroy');
    Route::post('services/update-order', [\App\Http\Controllers\Admin\ServiceController::class, 'updateOrder'])->name('admin.services.updateOrder');
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->names('admin.services');

    // Templates
    Route::post('templates/bulk-destroy', [\App\Http\Controllers\Admin\TemplateController::class, 'bulkDestroy'])->name('admin.templates.bulkDestroy');
    Route::post('templates/update-order', [\App\Http\Controllers\Admin\TemplateController::class, 'updateOrder'])->name('admin.templates.updateOrder');
    Route::resource('templates', \App\Http\Controllers\Admin\TemplateController::class)->names('admin.templates');
    
});

// AdminLTE Auth Routes (Auto-registered if adminlte:install auth was run, 
// but we might need to ensure standard auth routes exist)
// Auth::routes();

