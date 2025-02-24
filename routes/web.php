<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/signup', function () {
    return view('pages.Registration.signup');
});

Route::get('/signin', function () {
    return view('pages.Registration.signin');
});

Route::get('/users', function () {
    return view('pages.Users.users');
});
