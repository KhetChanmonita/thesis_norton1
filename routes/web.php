<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ContactController;

// Main routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/service', [ServiceController::class, 'index'])->name('service');

Route::get('/trucks_section', fn() => view('trucks_section'))->name('trucks_section');
Route::post('/trucks/book', [BookingController::class, 'storeTruckBooking'])->name('trucks.book');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/services_header', fn() => view('services_header'))->name('services_header');
Route::get('/about_us', fn() => view('about_us'))->name('about_us');
Route::get('/why-choose-us', fn() => view('whychooseus'))->name('whychooseus');
Route::get('/service/{type}', fn($type) => view('description_type', compact('type')))->name('service.detail');
Route::get('/price', [HomeController::class, 'price'])->name('price');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/import', [HomeController::class, 'import'])->name('import');
    Route::get('/export', [HomeController::class, 'export'])->name('export');
    Route::get('/history', [HomeController::class, 'history'])->name('history');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');

    // Booking routes
    Route::get('/bookings/import', [BookingController::class, 'createImport'])->name('bookings.create.import');
    Route::post('/bookings/import', [BookingController::class, 'storeImport'])->name('bookings.store.import');
    Route::get('/bookings/export', [BookingController::class, 'createExport'])->name('bookings.create.export');
    Route::post('/bookings/export', [BookingController::class, 'storeExport'])->name('bookings.store.export');
    Route::post('/booking/calculate', [BookingController::class, 'calculatePrice'])->name('booking.calculate');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payment routes
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{booking}', [PaymentController::class, 'success'])->name('booking.success');

    // Truck routes
    Route::get('/trucks/available', [TruckController::class, 'getAvailableTrucks'])->name('trucks.available');
    Route::get('/trucks', [TruckController::class, 'getAllTrucks'])->name('trucks.all');
});
