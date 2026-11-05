<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Public Housing Routes
Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/logement/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Student Bookings Routes (with authentication middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-reservations', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/logement/{property}/reserver', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/reservations/{booking}/annuler', [BookingController::class, 'cancel'])->name('bookings.cancel');
});
