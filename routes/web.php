<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes (No Login Required)
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ClientProductController::class, 'show'])->name('products.show');

// Protected Routes (Login Required)
Route::middleware(['auth'])->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    
    // Order Routes
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [ClientOrderController::class, 'store'])->name('orders.store');
    
    // Address routes
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // User Routes
    Route::get('/user/profile', [\App\Http\Controllers\Client\UserController::class, 'show'])->name('client.user.profile');
    Route::get('/user/edit', [\App\Http\Controllers\Client\UserController::class, 'edit'])->name('client.user.edit');
    Route::put('/user/update', [\App\Http\Controllers\Client\UserController::class, 'update'])->name('client.user.update');
    Route::get('/user/change-password', [\App\Http\Controllers\Client\UserController::class, 'showChangePassword'])->name('client.user.change_password');
    Route::post('/user/change-password', [\App\Http\Controllers\Client\UserController::class, 'changePassword'])->name('client.user.change_password.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('banners', BannerController::class);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');

    // Home Sections Management
    Route::resource('home-sections', HomeSectionController::class);

    Route::post('home-sections/update-order', [HomeSectionController::class, 'updateOrder'])->name('home-sections.update-order');
    Route::post('home-sections/{homeSection}/toggle-active', [HomeSectionController::class, 'toggleActive'])->name('home-sections.toggle-active');

    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
});

// Language Routes
Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

// Address API Routes (Public)
Route::get('/api/provinces', [AddressController::class, 'getProvinces'])->name('api.provinces');
Route::get('/api/districts', [AddressController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards', [AddressController::class, 'getWards'])->name('api.wards');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// File Manager Routes (for admin)
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
