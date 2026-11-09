<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Language Switcher Route
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'fr', 'ar'], true)) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

// Public Housing Routes
Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/logement/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Authentication & Registration Routes
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/connexion/code-email', [AuthController::class, 'sendOtpCode'])->name('login.otp.send');
Route::get('/connexion/verifier-code', [AuthController::class, 'showOtpForm'])->name('login.otp.form');
Route::post('/connexion/verifier-code', [AuthController::class, 'verifyOtpCode'])->name('login.otp.verify');
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Student Bookings Routes (with authentication middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-reservations', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/logement/{property}/reserver', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/reservations/{booking}/annuler', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// House Owner Routes (Manage properties and incoming bookings)
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    Route::get('/properties', [OwnerController::class, 'properties'])->name('properties.index');
    Route::get('/properties/create', [OwnerController::class, 'createProperty'])->name('properties.create');
    Route::post('/properties', [OwnerController::class, 'storeProperty'])->name('properties.store');
    Route::get('/properties/{property}/edit', [OwnerController::class, 'editProperty'])->name('properties.edit');
    Route::put('/properties/{property}', [OwnerController::class, 'updateProperty'])->name('properties.update');
    Route::post('/properties/{property}/toggle-availability', [OwnerController::class, 'toggleAvailability'])->name('properties.toggle-availability');
    Route::delete('/properties/{property}', [OwnerController::class, 'deleteProperty'])->name('properties.delete');
    Route::get('/bookings', [OwnerController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [OwnerController::class, 'updateBookingStatus'])->name('bookings.status');
});

// Unique Admin Dashboard Routes (Manage all users, all properties, all bookings)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/properties', [AdminController::class, 'properties'])->name('properties.index');
    Route::post('/properties/{property}/toggle-availability', [AdminController::class, 'togglePropertyAvailability'])->name('properties.toggle-availability');
    Route::delete('/properties/{property}', [AdminController::class, 'deleteProperty'])->name('properties.delete');

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');
});
