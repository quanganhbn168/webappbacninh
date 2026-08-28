<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\Frontend\OperationServiceController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\SiteController;
use App\Http\Controllers\Frontend\SiteIconController;
use App\Http\Controllers\Frontend\SiteManifestController;
use App\Http\Controllers\Frontend\ThemeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TenantRegisterController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\ToolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(SiteController::class)->group(function (): void {
    Route::get('/', 'home')->name('home');
    Route::get('/gioi-thieu', 'about')->name('about');
    Route::get('/lien-he', 'contact')->name('contact');
    Route::get('/bang-gia', 'pricing')->name('pricing');
    Route::get('/hop-tac-agency', 'agency')->name('agency');

    Route::get('/chinh-sach-bao-mat', 'legal')->defaults('slug', 'chinh-sach-bao-mat')->name('legal.privacy');
    Route::get('/dieu-khoan-su-dung', 'legal')->defaults('slug', 'dieu-khoan-su-dung')->name('legal.terms');
    Route::get('/chinh-sach-bao-hanh', 'legal')->defaults('slug', 'chinh-sach-bao-hanh')->name('legal.warranty');
    Route::get('/quy-trinh-thanh-toan', 'legal')->defaults('slug', 'quy-trinh-thanh-toan')->name('legal.payment');
});

Route::get('/thiet-ke-website', [ServiceController::class, 'index'])->name('services.index');
Route::get('/thiet-ke-website/{slug}', [ServiceController::class, 'detail'])->name('services.show');

Route::get('/kho-giao-dien', [ThemeController::class, 'index'])->name('themes.index');
Route::get('/kho-giao-dien/{slug}', [ThemeController::class, 'detail'])->name('themes.show');

Route::get('/du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/du-an/{slug}', [ProjectController::class, 'detail'])->name('projects.show');

Route::get('/kien-thuc', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/kien-thuc/{slug}', [ArticleController::class, 'detail'])->name('articles.show');

Route::get('/dich-vu-van-hanh', [OperationServiceController::class, 'index'])->name('operations.index');
Route::get('/dich-vu-van-hanh/{slug}', [OperationServiceController::class, 'detail'])->name('operations.show');

Route::post('/lien-he', [LeadController::class, 'store'])->name('leads.store');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/site.webmanifest', SiteManifestController::class)->name('site.manifest');
Route::get('/favicon.ico', [SiteIconController::class, 'favicon'])->name('site.favicon');
Route::get('/apple-touch-icon.png', [SiteIconController::class, 'appleTouchIcon'])->name('site.apple-touch-icon');
Route::get('/apple-touch-icon-precomposed.png', [SiteIconController::class, 'appleTouchIcon'])->name('site.apple-touch-icon-precomposed');
Route::redirect('/contact', '/lien-he', 301);
Route::redirect('/blog', '/kien-thuc', 301);
Route::get('/blog/{slug}', fn (string $slug) => redirect()->route('articles.show', $slug, 301));
Route::get('/search', fn () => redirect()->route('articles.index'))->name('search');

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('/auth/{provider}', [CustomerAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [CustomerAuthController::class, 'handleProviderCallback']);
Route::get('/domain-check', [DomainController::class, 'check'])->name('domain.check');

Route::get('/anh-cover', [ThumbnailController::class, 'showCoverPage'])->name('cover.page');
Route::post('/get-info', [ThumbnailController::class, 'getInfo'])->name('cover.getInfo');
Route::post('/download-thumbnail', [ThumbnailController::class, 'download'])->name('cover.download');
Route::post('/download-video', [ThumbnailController::class, 'downloadVideo'])->name('cover.download.video');
Route::get('/bulk-anh-cover', [ThumbnailController::class, 'showBulkCoverPage'])->name('cover.bulk.page');
Route::post('/download-bulk', [ThumbnailController::class, 'downloadBulk'])->name('cover.download.bulk');

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
});

Route::post('/subscribe', fn () => back()->with('success', 'Cảm ơn bạn đã để lại email, chúng tôi sẽ liên hệ sớm!'))->name('subscribe.email');
Route::post('/create-tenant', [TenantRegisterController::class, 'store']);

$legacyRedirects = [
    'index.php' => 'home',
    'gioi-thieu.php' => 'about',
    'lien-he.php' => 'contact',
    'bang-gia.php' => 'pricing',
    'hop-tac-agency.php' => 'agency',
    'thiet-ke-website.php' => 'services.index',
    'kho-giao-dien.php' => 'themes.index',
    'du-an.php' => 'projects.index',
    'kien-thuc.php' => 'articles.index',
    'dich-vu-van-hanh.php' => 'operations.index',
];
foreach ($legacyRedirects as $uri => $routeName) {
    Route::get('/'.$uri, fn () => redirect()->route($routeName, status: 301));
}

foreach (config('website_services') as $service) {
    Route::get('/'.$service['route'], fn () => redirect()->route('services.show', $service['slug'], 301));
}
foreach (config('operation_services') as $service) {
    $slug = pathinfo($service['route'], PATHINFO_FILENAME);
    Route::get('/'.$service['route'], fn () => redirect()->route('operations.show', $slug, 301));
}
$legalRoutes = [
    'chinh-sach-bao-mat' => 'legal.privacy',
    'dieu-khoan-su-dung' => 'legal.terms',
    'chinh-sach-bao-hanh' => 'legal.warranty',
    'quy-trinh-thanh-toan' => 'legal.payment',
];
foreach ($legalRoutes as $slug => $routeName) {
    Route::get('/'.$slug.'.php', fn () => redirect()->route($routeName, status: 301));
}

Route::get('/chi-tiet-giao-dien.php', fn (Request $request) => redirect()->route('themes.show', $request->string('slug'), 301));
Route::get('/chi-tiet-du-an.php', fn (Request $request) => redirect()->route('projects.show', $request->string('slug'), 301));
Route::get('/chi-tiet-bai-viet.php', fn (Request $request) => redirect()->route('articles.show', $request->string('slug'), 301));
