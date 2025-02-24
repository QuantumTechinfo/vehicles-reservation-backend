<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use User\Controllers\UserController;

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
require base_path('packages/users/src/routes/api.php');

Route::prefix('v1')->group(function () {
    Route::post('login', [App\Http\Controllers\LoginController::class, 'login'])->name('v1.login.login');
    // Route::post('signup', [packages\src\\UserController::class, 'create'])->name('v1.login.create');


    Route::middleware('auth:api')->group(function () {
        Route::post('setToken', [App\Http\Controllers\LoginController::class, 'setToken'])->name('v1.login.token');
        Route::post('logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('v1.login.logout');
    });
});
