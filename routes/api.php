<?php

use App\Http\Controllers\Api\BookingApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Bookings
    Route::get('/bookings',              [BookingApiController::class, 'index']);
    Route::get('/bookings/{id}',         [BookingApiController::class, 'show']);
    Route::get('/bookings/track/{token}',[BookingApiController::class, 'track']);
    Route::post('/bookings',             [BookingApiController::class, 'store']);

});