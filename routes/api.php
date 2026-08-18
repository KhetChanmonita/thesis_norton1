<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ImportBookingApiController;
use App\Http\Controllers\Api\MobileBookingApiController;
use App\Http\Controllers\Api\ShippingRateApiController;
use App\Http\Controllers\Api\TruckApiController;
use Illuminate\Support\Facades\Route;

// ── Public booking tracking (used by web customers) ──────────────────────────
Route::prefix('v1')->group(function () {
    Route::get('/bookings',               [BookingApiController::class, 'index']);
    Route::get('/bookings/track/{token}', [BookingApiController::class, 'track']);
    Route::get('/bookings/{id}',          [BookingApiController::class, 'show']);
    Route::post('/bookings',              [BookingApiController::class, 'store']);
});

// ── Mobile app – auth ─────────────────────────────────────────────────────────
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login',    [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout',          [AuthApiController::class, 'logout']);
    Route::get('/me',               [AuthApiController::class, 'me']);
    Route::put('/profile',          [AuthApiController::class, 'updateProfile']);
    Route::post('/change-password', [AuthApiController::class, 'changePassword']);

    // Trucks & shipping rates
    Route::get('/trucks',         [TruckApiController::class, 'index']);
    Route::get('/shipping-rates', [ShippingRateApiController::class, 'index']);

    // Import bookings (mobile CRUD)
    Route::get('/import-bookings',       [ImportBookingApiController::class, 'index']);
    Route::get('/import-bookings/{id}',  [ImportBookingApiController::class, 'show']);
    Route::post('/import-bookings',      [ImportBookingApiController::class, 'store']);
    Route::put('/import-bookings/{id}',  [ImportBookingApiController::class, 'update']);
    Route::delete('/import-bookings/{id}', [ImportBookingApiController::class, 'destroy']);

    // Bookings (mobile actions)
    Route::get('/bookings',                        [MobileBookingApiController::class, 'index']);
    Route::get('/bookings/{id}',                   [MobileBookingApiController::class, 'show']);
    Route::patch('/bookings/{id}/confirm',         [MobileBookingApiController::class, 'confirm']);
    Route::patch('/bookings/{id}/start',           [MobileBookingApiController::class, 'start']);
    Route::patch('/bookings/{id}/complete',        [MobileBookingApiController::class, 'complete']);
    Route::patch('/bookings/{id}/cancel',          [MobileBookingApiController::class, 'cancel']);
    Route::get('/bookings/{id}/payments',          [MobileBookingApiController::class, 'getPayments']);
    Route::post('/bookings/{id}/payments',         [MobileBookingApiController::class, 'submitPayment']);
});