<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController as UserController;
use App\Http\Controllers\VehicleController as VehicleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    dd(auth()->user());
    return view(view: 'dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin panel user controller
Route::get('/users', [UserController::class, 'index'])->middleware('auth')->name('users');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware('auth')
    ->name('users.destroy');

// Vehicle Controller
Route::get('/vehicles', [VehicleController::class, 'index'])->middleware('auth')->name('vehicles');

// Tick if Reservation 
Route::get('/reservations', [ReservationController::class, 'index'])->middleware('auth')->name('reservations');
Route::put('/reservation/{id}', [ReservationController::class, 'update'])
    ->middleware('auth')
    ->name('reservation.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
