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

use User\Controllers\ProfileController;
use User\Controllers\RoleController;
use User\Controllers\UserController;
use User\Controllers\PermissionController;

Route::get('hello', function () {
    return response()->json(['message' => 'Hello, World!']);
});

// These routes will now have unique names like "api.v1.roles.index"
Route::resource('permissions', PermissionController::class);
Route::apiResource('roles', RoleController::class)->except('create');
Route::apiResource('user', UserController::class)->except('create');
Route::put('update-password/{user}', [ProfileController::class, 'update_password'])->name('update_password');
Route::get('drivers', [UserController::class, 'getDriver'])->name('drivers.get');