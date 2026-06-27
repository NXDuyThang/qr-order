<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BookingController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/restaurant', [PageController::class, 'restaurantHome'])->name('restaurant_home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/product/{slug}', [PageController::class, 'productDetail'])->name('product.detail');
Route::get('/booking', [PageController::class, 'booking'])->name('booking');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/vietnamese-cuisine', [PageController::class, 'vietnameseCuisine'])->name('vietnamese_cuisine');
Route::get('/vietnamese-cuisine/{slug}', [PageController::class, 'vietnameseCuisineDetail'])->name('vietnamese_cuisine_detail');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile Routes
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/update-info', [ProfileController::class, 'updateInfo'])->name('update_info');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update_password');
    Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update_avatar');
    Route::post('/update-cccd', [ProfileController::class, 'updateCccd'])->name('update_cccd');
});

// Wishlist Routes
use App\Http\Controllers\WishlistController;
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Table Ordering & Checkout Routes (Guest allowed)
Route::get('/order', [PageController::class, 'orderAtTable'])->name('order_at_table');
Route::post('/checkout/prepare', [\App\Http\Controllers\OrderController::class, 'prepareCheckout'])->name('checkout.prepare');
Route::get('/checkout', [\App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
Route::get('/checkout/transfer/{order}', [\App\Http\Controllers\OrderController::class, 'showTransferQR'])->name('checkout.transfer');

// API Routes for frontend
Route::post('/ajax/administratives', [ProfileController::class, 'getAdministratives'])->name('api.administratives');
Route::post('/ajax/fetch-avatar', function (\Illuminate\Http\Request $request) {
    $response = \Illuminate\Support\Facades\Http::post('https://account.nks.vn/api/nks/user/login', [
        'username' => $request->username,
        'password' => $request->password,
    ]);
    return $response->json();
})->name('api.fetch_avatar');

// Chatbot Routes
use App\Http\Controllers\ChatbotController;
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/send', [ChatbotController::class, 'chat'])->name('chatbot.send');
