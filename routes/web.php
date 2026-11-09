<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
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

// Authentication Routes
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Student Bookings Routes (with authentication middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-reservations', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/logement/{property}/reserver', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/reservations/{booking}/annuler', [BookingController::class, 'cancel'])->name('bookings.cancel');
});
