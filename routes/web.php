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
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('filament.admin.auth.logout');
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

// Table Ordering & Checkout Routes (Must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/order', [PageController::class, 'orderAtTable'])->name('order_at_table');
    Route::post('/order/store', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
    Route::get('/order/track/{order}', [\App\Http\Controllers\OrderController::class, 'track'])->name('order.track');
    Route::post('/order/{order}/payment-method', [\App\Http\Controllers\OrderController::class, 'updatePaymentMethod'])->name('order.update_payment_method');
    Route::get('/checkout/transfer/{order}', [\App\Http\Controllers\OrderController::class, 'showTransferQR'])->name('checkout.transfer');
    Route::post('/checkout/transfer/{order}/confirm', [\App\Http\Controllers\OrderController::class, 'confirmTransfer'])->name('checkout.transfer.confirm');
    Route::post('/order/{order}/item/{item}/cancel', [\App\Http\Controllers\OrderController::class, 'cancelItem'])->name('order.item.cancel');
    Route::post('/order/{order}/item/{item}/reduce', [\App\Http\Controllers\OrderController::class, 'reduceItem'])->name('order.item.reduce');
    Route::post('/order/{order}/item/{item}/update-quantity', [\App\Http\Controllers\OrderController::class, 'updateQuantity'])->name('order.item.update_quantity');
});

// Order tracking API (Must be outside auth middleware so guests can poll)
Route::get('/api/order/{order}/status', [\App\Http\Controllers\OrderController::class, 'getStatus'])->name('api.order.status');

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

// Employee Routes
use App\Http\Controllers\EmployeeController;
Route::middleware(['auth', 'role:chef'])->group(function () {
    Route::get('/chef', function () { return redirect('/admin/employee-dashboard'); })->name('chef.dashboard');
});

Route::middleware(['auth', 'role:waiter'])->group(function () {
    Route::get('/waiter', function () { return redirect('/admin/employee-dashboard'); })->name('waiter.dashboard');
});

// POS Routes for Waiter/Manager/Admin
Route::middleware('auth')->group(function () {
    Route::get('/staff/pos', [\App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::post('/staff/pos/table/{table}/order', [\App\Http\Controllers\POSController::class, 'createOrder'])->name('pos.create_order');
    Route::get('/staff/pos/table/{table}/order', [\App\Http\Controllers\POSController::class, 'tableOrder'])->name('pos.table_order');
    Route::post('/staff/pos/order-item/{item}/serve', [\App\Http\Controllers\POSController::class, 'serveItem'])->name('pos.serve_item');
});

// Common Employee Actions (Check-in/out, Leave)
Route::middleware('auth')->group(function () {
    Route::post('/employee/check-in', [EmployeeController::class, 'checkIn'])->name('employee.check_in');
    Route::post('/employee/check-out', [EmployeeController::class, 'checkOut'])->name('employee.check_out');
    Route::post('/employee/leave-request', [EmployeeController::class, 'submitLeaveRequest'])->name('employee.leave_request');
    Route::get('/employee/leave-history', function () { return redirect('/admin/leave-history'); })->name('employee.leave_history');
    Route::get('/employee/my-salary', function () { return redirect('/admin/my-salary'); })->name('employee.my_salary');
    Route::get('/employee/timekeeping-history', function () { return redirect('/admin/timekeeping-history'); })->name('employee.timekeeping_history');
});
