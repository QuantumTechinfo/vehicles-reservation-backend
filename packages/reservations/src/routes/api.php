<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use Reservation\Controllers\ReservationController;
use Reservation\Controllers\UpdateReservationStatusController;

Route::apiResource('reservations',ReservationController::class)->except('create');
Route::put('update-status-reservation/{reservation}',[UpdateReservationStatusController::class,'updateStatus']);
Route::get('user-reservation',[ReservationController::class,'userReservation']);
