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
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Client\AiAssistantController;
use App\Http\Controllers\Client\PaymentController;

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
Route::post('/ai/consult', [AiAssistantController::class, 'consult'])->name('ai.consult');
Route::view('/wishlist', 'client.wishlist')->name('wishlist.index');

// MoMo Payment IPN & Return (public, KHÔNG yêu cầu auth)
Route::post('/payment/momo/ipn', [PaymentController::class, 'momoIpn'])->name('payment.momo.ipn');
Route::match(['get', 'post'], '/payment/momo/return', [PaymentController::class, 'momoReturn'])->name('payment.momo.return');

// Protected Routes (Login Required)
Route::middleware(['auth'])->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher'])->name('cart.voucher.apply');
    Route::delete('/cart/voucher', [CartController::class, 'removeVoucher'])->name('cart.voucher.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    
    // Order Routes
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [ClientOrderController::class, 'exportInvoice'])->name('orders.invoice');
    Route::post('/orders', [ClientOrderController::class, 'store'])->name('orders.store');

    // MoMo Payment (cần auth)
    Route::get('/payment/momo/{order}', [PaymentController::class, 'momoShow'])->name('payment.momo.show');
    Route::get('/payment/momo/{order}/status', [PaymentController::class, 'momoStatus'])->name('payment.momo.status');
    Route::post('/payment/momo/{order}/mock-success', [PaymentController::class, 'momoMockSuccess'])->name('payment.momo.mock');

    // Bank Transfer Payment (cần auth)
    Route::get('/payment/bank/{order}', [PaymentController::class, 'bankShow'])->name('payment.bank.show');
    Route::post('/payment/bank/{order}/notify', [PaymentController::class, 'bankNotify'])->name('payment.bank.notify');
    Route::post('/payment/bank/{order}/mock-success', [PaymentController::class, 'bankMockSuccess'])->name('payment.bank.mock');
    
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

    // Game Routes
    Route::get('/game', [\App\Http\Controllers\Client\GameController::class, 'index'])->name('game.index');
    Route::post('/game/question', [\App\Http\Controllers\Client\GameController::class, 'getQuestion'])->name('game.question');
    Route::post('/game/answer', [\App\Http\Controllers\Client\GameController::class, 'answerQuestion'])->name('game.answer');
    Route::post('/game/shake', [\App\Http\Controllers\Client\GameController::class, 'shakeJar'])->name('game.shake');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.exportPdf');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid'])->name('orders.markPaid');
    Route::post('/orders/{order}/mark-unpaid', [OrderController::class, 'markUnpaid'])->name('orders.markUnpaid');
    Route::resource('banners', BannerController::class);
    Route::resource('vouchers', VoucherController::class);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::post('products/{product}/upload-gallery', [ProductController::class, 'uploadGallery'])->name('products.uploadGallery');
    Route::delete('products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    // Game Management
    Route::prefix('game')->name('game.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\GameController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\GameController::class, 'update'])->name('update');
        Route::get('/questions', [\App\Http\Controllers\Admin\GameController::class, 'questions'])->name('questions');
        Route::post('/questions', [\App\Http\Controllers\Admin\GameController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/questions/{question}', [\App\Http\Controllers\Admin\GameController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{question}', [\App\Http\Controllers\Admin\GameController::class, 'destroyQuestion'])->name('questions.destroy');
    });

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
