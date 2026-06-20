<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ─── Language Switch ──────────────────────────────────────────────────────────
Route::get('/locale/{locale}', [HomeController::class, 'setLocale'])->name('locale.set');

// ─── Frontend ─────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{product}/purchase', [ShopController::class, 'purchase'])->name('shop.purchase')->middleware('auth');
Route::get('/shop/{product}/download', [ShopController::class, 'download'])->name('shop.download')->middleware('auth');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Courses
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])
    ->name('courses.enroll')
    ->middleware('auth');

// Orders
Route::get('/order', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success/{orderNumber}', [OrderController::class, 'success'])->name('order.success');
Route::post('/order/calculate-price', [OrderController::class, 'calculatePrice'])->name('order.calculate');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [App\Http\Controllers\Auth\LoginController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [App\Http\Controllers\Auth\LoginController::class, 'changePassword'])->name('password.change.submit');
});

// ─── Admin Panel ──────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:super-admin|admin|editor'])
    ->group(function () {

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Artworks
        Route::resource('artworks', Admin\ArtworkController::class);

        // Orders
        Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('orders/{order}/pricing', [Admin\OrderController::class, 'updatePricing'])->name('orders.pricing');

        // Users (super-admin only)
        Route::middleware('role:super-admin|admin')->group(function () {
            Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
            Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
            Route::patch('users/{user}/status', [Admin\UserController::class, 'updateStatus'])->name('users.status');
            Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
        });

        // Blog
        Route::resource('blog', Admin\BlogController::class);

        // Products
        Route::resource('products', Admin\ProductController::class);

        // Courses
        Route::resource('courses', Admin\CourseController::class);

        // Messages
        Route::get('messages', [Admin\MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [Admin\MessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/reply', [Admin\MessageController::class, 'reply'])->name('messages.reply');

        // Settings (super-admin only)
        Route::middleware('role:super-admin')->group(function () {
            Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::post('settings/pricing', [Admin\SettingController::class, 'updatePricing'])->name('settings.pricing');
            Route::post('settings/materials', [Admin\SettingController::class, 'storeMaterial'])->name('settings.materials.store');
            Route::delete('settings/materials/{material}', [Admin\SettingController::class, 'destroyMaterial'])->name('settings.materials.destroy');
        });
    });

// ─── SEO ─────────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots']);
