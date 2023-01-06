<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Views
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
 */

// Create account
Route::post('/createAccount', [RegisterController::class, 'createAccount'])->name('createAccount')->middleware('guest');

// Activate account
Route::patch('/activateAccount/{verificationCode}', [RegisterController::class, 'activateAccount'])->name('activateAccount')->middleware(['guest']);
