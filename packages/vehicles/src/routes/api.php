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

use Vehicle\Controllers\VehicleController;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('vehicles', VehicleController::class)->except('create');
});