<?php

use App\Enums\SocialProvider;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LandingPageController;
use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\Frontend\LegacyArticleRedirectController;
use App\Http\Controllers\Frontend\OperationServiceController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\SiteIconController;
use App\Http\Controllers\Frontend\SiteManifestController;
use App\Http\Controllers\Frontend\ThemeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TenantRegisterController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

// Images moved from /frontend/images and /frontend/assets/images to /images; keep old links working.
Route::get('/frontend/{path}', fn (string $path) => redirect('/images/'.basename($path), 301))
    ->where('path', '(assets/)?images/[A-Za-z0-9._-]+');

Route::get('/landing/{slug}', [LandingPageController::class, 'show'])->name('landing.show');

Route::get('/profile/bac-be-bong', function () {
    return view('site.profile.bac-be-bong');
});
Route::get('/', HomeController::class)->name('home');

Route::controller(PageController::class)->group(function (): void {
    Route::get('/dich-vu', 'services')->name('services.overview');
    Route::get('/hosting-domain-email', 'hosting')->name('hosting');
    Route::get('/giai-phap', 'solutions')->name('solutions');
    Route::get('/san-pham', 'products')->name('products');
    Route::get('/bang-gia', 'pricing')->name('pricing');
    Route::get('/lien-he', 'contact')->name('contact');
    Route::get('/gioi-thieu', 'about')->name('about');
    Route::get('/hop-tac-agency', 'agency')->name('agency');
    foreach ([
        'chinh-sach-bao-mat' => ['privacy', 'legal.privacy'],
        'dieu-khoan-su-dung' => ['terms', 'legal.terms'],
        'chinh-sach-bao-hanh' => ['warranty', 'legal.warranty'],
        'quy-trinh-thanh-toan' => ['payment-process', 'legal.payment'],
    ] as $slug => [$key, $routeName]) {
        Route::get('/'.$slug, 'legal')->defaults('key', $key)->name($routeName);
    }
});

Route::get('/du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/blog', [ArticleController::class, 'index'])->name('articles.index');
// The blog moved from /kien-thuc to /blog and the site root; Google still shows the old links.
Route::redirect('/kien-thuc', '/blog', 301);
Route::redirect('/tin-tuc', '/blog', 301);
Route::get('/kien-thuc/danh-muc/{slug}', [LegacyArticleRedirectController::class, 'category'])->where('slug', '[a-z0-9-]+');
Route::get('/kien-thuc/{slug}', [LegacyArticleRedirectController::class, 'post'])->where('slug', '[a-z0-9-]+');
Route::get('/cong-cu', [ToolController::class, 'index'])->name('tools.index');

Route::get('/thiet-ke-website', [ServiceController::class, 'index'])->name('services.index');
Route::get('/thiet-ke-website/{slug}', [ServiceController::class, 'detail'])->name('services.show');
Route::get('/dich-vu/{slug}', [ServiceController::class, 'general'])->name('services.detail');

Route::get('/kho-giao-dien', [ThemeController::class, 'index'])->name('themes.index');
Route::get('/kho-giao-dien/{slug}', [ThemeController::class, 'detail'])->name('themes.show');

Route::get('/du-an/{slug}', [ProjectController::class, 'detail'])->name('projects.show');

Route::get('/dich-vu-van-hanh', [OperationServiceController::class, 'index'])->name('operations.index');
Route::get('/dich-vu-van-hanh/{slug}', [OperationServiceController::class, 'detail'])->name('operations.show');
Route::post('/lien-he', [LeadController::class, 'store'])->middleware('throttle:6,1')->name('leads.store');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/site.webmanifest', SiteManifestController::class)->name('site.manifest');
Route::get('/favicon.ico', [SiteIconController::class, 'favicon'])->name('site.favicon');
Route::get('/apple-touch-icon.png', [SiteIconController::class, 'appleTouchIcon'])->name('site.apple-touch-icon');
Route::get('/apple-touch-icon-precomposed.png', [SiteIconController::class, 'appleTouchIcon'])->name('site.apple-touch-icon-precomposed');
Route::redirect('/contact', '/lien-he', 301);
Route::get('/search', fn () => redirect()->route('articles.index'))->name('search');

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('/auth/{provider}', [CustomerAuthController::class, 'redirectToProvider'])->name('social.login')->whereIn('provider', array_column(SocialProvider::cases(), 'value'));
Route::get('/auth/{provider}/callback', [CustomerAuthController::class, 'handleProviderCallback'])->name('social.callback')->whereIn('provider', array_column(SocialProvider::cases(), 'value'));
Route::get('/domain-check', [DomainController::class, 'check'])->name('domain.check');

Route::get('/anh-cover', [ThumbnailController::class, 'showCoverPage'])->name('cover.page');
Route::get('/bulk-anh-cover', [ThumbnailController::class, 'showBulkCoverPage'])->name('cover.bulk.page');
// These call YouTube/TikTok and download files on the visitor's behalf: rate limited per IP.
Route::middleware('throttle:60,1')->group(function (): void {
    Route::post('/get-info', [ThumbnailController::class, 'getInfo'])->name('cover.getInfo');
    Route::post('/download-thumbnail', [ThumbnailController::class, 'download'])->name('cover.download');
});
Route::middleware('throttle:10,1')->group(function (): void {
    Route::post('/download-video', [ThumbnailController::class, 'downloadVideo'])->name('cover.download.video');
    Route::post('/download-bulk', [ThumbnailController::class, 'downloadBulk'])->name('cover.download.bulk');
});

Route::get('/tinh-thue-tncn', [TaxController::class, 'showPersonalTax'])->name('tools.tax');
Route::post('/tinh-thue-tncn/calculate', [TaxController::class, 'calculatePersonalTax'])->name('tools.tax.calculate');
Route::get('/tinh-thue-ho-kinh-doanh', [TaxController::class, 'showHouseholdTax'])->name('tools.tax.household');
Route::post('/tinh-thue-ho-kinh-doanh/calculate', [TaxController::class, 'calculateHouseholdTax'])->name('tools.tax.household.calculate');
Route::get('/tinh-thue-doanh-nghiep', [TaxController::class, 'showSMETax'])->name('tools.tax.sme');
Route::post('/tinh-thue-doanh-nghiep/calculate', [TaxController::class, 'calculateSMETax'])->name('tools.tax.sme.calculate');

Route::prefix('tools')->group(function (): void {
    Route::get('/qr-code', [ToolController::class, 'qrCode'])->name('tools.qr');
    Route::get('/ngan-hang', [ToolController::class, 'bankQr'])->name('tools.bank-qr');
    Route::get('/calendar', [ToolController::class, 'calendar'])->name('tools.calendar');
    Route::get('/vong-quay-an-trua', [ToolController::class, 'foodWheel'])->name('tools.food-wheel');
});

Route::prefix('payment')->group(function (): void {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/callback/{provider}', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/result', [PaymentController::class, 'result'])->name('payment.result');
    Route::post('/api/process', [PaymentController::class, 'processApi'])->name('payment.api.process');
    Route::get('/sepay/qr', [PaymentController::class, 'sepayQr'])->name('payment.sepay.qr');
    Route::get('/sepay/status/{orderId}', [PaymentController::class, 'sepayStatus'])->name('payment.sepay.status')->middleware('throttle:30,1');
});

Route::post('/subscribe', fn () => back()->with('success', 'Cảm ơn bạn đã để lại email, chúng tôi sẽ liên hệ sớm!'))->name('subscribe.email');
// Each call creates and migrates a database, so only signed-in admins may use it
// until public sign-up for the SaaS plan is designed.
Route::post('/create-tenant', [TenantRegisterController::class, 'store'])->middleware(['auth:admin', 'throttle:5,1']);
