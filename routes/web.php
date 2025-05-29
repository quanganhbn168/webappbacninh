<?php

use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// Tìm kiếm, đăng nhập, đăng ký (giữ nguyên)
Route::get('/search', fn() => view('frontend.index'))->name('search');
Route::get('/login', fn() => view('frontend.index'))->name('login');
Route::get('/register', fn() => view('frontend.index'))->name('register');
Route::get('/contact', fn() => view('frontend.index'))->name('contact');

// Blog
Route::get('/blog', fn() => view('frontend.index'))->name('blog.index');

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
