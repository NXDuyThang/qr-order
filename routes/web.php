<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BookingController;

use App\Http\Controllers\OrderController;

Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/restaurant', [PageController::class, 'restaurantHome'])->name('restaurant_home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/booking', [PageController::class, 'booking'])->name('booking');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/order', [PageController::class, 'orderAtTable'])->name('order_at_table');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/vietnamese-cuisine', [PageController::class, 'vietnameseCuisine'])->name('vietnamese_cuisine');
Route::get('/vietnamese-cuisine/{slug}', [PageController::class, 'vietnameseCuisineDetail'])->name('vietnamese_cuisine_detail');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
