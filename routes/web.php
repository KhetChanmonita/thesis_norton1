<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TruckAdminController;
use App\Http\Controllers\Admin\DriverAdminController;
use App\Http\Controllers\Admin\CustomerAdminController;
use App\Http\Controllers\Admin\ScheduleAdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\PaymentAdminController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\ExpenseAdminController;
use App\Http\Controllers\Admin\CostSheetAdminController;
use App\Models\Truck;

// Main routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/service', [ServiceController::class, 'index'])->name('service');

Route::get('/trucks_section', [TruckController::class, 'customerIndex'])->name('trucks_section');
Route::get('/my-bookings',          [BookingController::class, 'myBookings'])->name('my.bookings');
Route::get('/booking/{id}/status',  [BookingController::class, 'trackBooking'])->name('booking.track');
Route::get('/booking/{id}/pay',     [BookingController::class, 'showPayment'])->name('booking.pay');
Route::post('/booking/{id}/pay',    [BookingController::class, 'processPayment'])->name('booking.pay.process');
Route::post('/booking/extra-charges/{extraCharge}/respond', [BookingController::class, 'respondExtraCharge'])->name('booking.extra-charge.respond');
Route::get('/contact',  [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/services_header', fn() => view('services_header'))->name('services_header');
Route::get('/about_us', fn() => view('about_us'))->name('about_us');
Route::get('/why-choose-us', fn() => view('whychooseus', [
    'truckCount' => Truck::count(),
]))->name('whychooseus');
Route::get('/service/{type}', fn($type) => view('description_type', compact('type')))->name('service.detail');
Route::get('/price', [HomeController::class, 'price'])->name('price');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // Forgot password (guest only)
    Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
});

// Reset password (accessible whether logged in or not)
Route::get('/reset-password/{token}',  [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password',         [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/import', [HomeController::class, 'import'])->name('import');
    Route::get('/export', [HomeController::class, 'export'])->name('export');
    Route::get('/history', [HomeController::class, 'history'])->name('history');
    Route::get('/profile',  [HomeController::class, 'profile'])->name('profile');
    Route::post('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/forgot-password', [HomeController::class, 'sendProfilePasswordReset'])->name('profile.forgot-password');
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');

    // Truck booking submission (requires login)
    Route::post('/trucks/book', [BookingController::class, 'storeTruckBooking'])->name('trucks.book');

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


// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                [AdminController::class,    'dashboard'])->name('dashboard');

    // Trucks
    Route::get('/trucks',          [TruckAdminController::class,    'index'])->name('trucks.index');
    Route::post('/trucks',         [TruckAdminController::class,    'store'])->name('trucks.store');
    Route::put('/trucks/{truck}',         [TruckAdminController::class, 'update'])->name('trucks.update');
    Route::patch('/trucks/{truck}/status',[TruckAdminController::class, 'updateStatus'])->name('trucks.status');
    Route::delete('/trucks/{truck}',      [TruckAdminController::class, 'destroy'])->name('trucks.destroy');

    // Drivers
    Route::get('/drivers',           [DriverAdminController::class,   'index'])->name('drivers.index');
    Route::post('/drivers',          [DriverAdminController::class,   'store'])->name('drivers.store');
    Route::put('/drivers/{driver}',  [DriverAdminController::class,   'update'])->name('drivers.update');
    Route::delete('/drivers/{driver}',[DriverAdminController::class,  'destroy'])->name('drivers.destroy');

    // Schedules
    Route::get('/schedules',              [ScheduleAdminController::class,  'index'])->name('schedules.index');
    Route::post('/schedules',             [ScheduleAdminController::class,  'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}',   [ScheduleAdminController::class,  'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}',[ScheduleAdminController::class,  'destroy'])->name('schedules.destroy');

    // Customers
    Route::get('/customers',                      [CustomerAdminController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}/edit',      [CustomerAdminController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}',           [CustomerAdminController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}',        [CustomerAdminController::class, 'destroy'])->name('customers.destroy');

    // Bookings
    Route::get('/bookings',                    [BookingAdminController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [BookingAdminController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}',       [BookingAdminController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/bookings/{booking}/extra-charges', [BookingAdminController::class, 'storeExtraCharge'])->name('bookings.extra-charge.store');

    // Payments & History
    Route::get('/payments',                          [PaymentAdminController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/verify',        [PaymentAdminController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject',        [PaymentAdminController::class, 'reject'])->name('payments.reject');
    Route::delete('/payments/{payment}',             [PaymentAdminController::class, 'destroy'])->name('payments.destroy');
    Route::get('/history',                           [PaymentAdminController::class, 'history'])->name('history.index');

    // Shipping Rates
    Route::get('/shipping-rates',        [\App\Http\Controllers\Admin\ShippingRateAdminController::class, 'index'])->name('shipping.index');
    Route::put('/shipping-rates/{rate}', [\App\Http\Controllers\Admin\ShippingRateAdminController::class, 'update'])->name('shipping.update');

    // Contact Messages
    Route::get('/messages',                  [ContactAdminController::class, 'index'])->name('messages.index');
    Route::patch('/messages/{message}/status',[ContactAdminController::class, 'updateStatus'])->name('messages.status');
    Route::delete('/messages/{message}',     [ContactAdminController::class, 'destroy'])->name('messages.destroy');

    // Expense Report
    Route::get('/reports',           [ExpenseAdminController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue',   [ExpenseAdminController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/revenue/print', [ExpenseAdminController::class, 'revenuePrint'])->name('reports.revenue.print');
    Route::get('/reports/profit',    [ExpenseAdminController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/profit/print', [ExpenseAdminController::class, 'profitPrint'])->name('reports.profit.print');
    Route::get('/reports/cost-sheet',                      [CostSheetAdminController::class, 'index'])->name('reports.cost-sheet');
    Route::post('/reports/cost-sheet/{booking}',           [CostSheetAdminController::class, 'update'])->name('reports.cost-sheet.update');
    Route::get('/reports/cost-sheet/{booking}/invoice',    [CostSheetAdminController::class, 'invoice'])->name('reports.invoice');
    Route::post('/reports',          [ExpenseAdminController::class, 'store'])->name('reports.store');
    Route::put('/reports/{expense}', [ExpenseAdminController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{expense}', [ExpenseAdminController::class, 'destroy'])->name('reports.destroy');
});
// ==================== END ADMIN ROUTES ====================
