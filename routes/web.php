<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Index
Route::view('/', 'index')->name('index')->middleware(['auth']);

// Login
Route::view('/login', 'login')->name('login')->middleware(['guest']);

// Register
Route::view('/register', 'register')->name('register')->middleware(['guest']);

// Reset password
Route::view('/resetPassword', 'resetPassword')->name('resetPassword')->middleware(['guest']);

// Reset password finish
Route::view('/resetPasswordFinish', 'resetPasswordFinish')->name('resetPasswordFinish')->middleware(['guest']);
