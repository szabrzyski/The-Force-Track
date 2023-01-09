<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Views
|--------------------------------------------------------------------------
 */

// Issues
Route::view('/', 'issues')->name('issues')->middleware(['auth']);

// Add issue
Route::view('/issues/add', 'addIssue')->name('addIssue')->middleware(['auth','nonAdmin']);

// Login
Route::view('/login', 'login')->name('login')->middleware(['guest']);

// Register
Route::view('/register', 'register')->name('register')->middleware(['guest']);

// Reset password
Route::view('/resetPassword', 'resetPassword')->name('resetPassword')->middleware(['guest']);

// Reset password finish
Route::view('/setNewPassword/{verificationCode}', 'resetPasswordFinish')->name('setNewPassword')->middleware(['guest']);

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
 */

// Test
Route::get('/test', [TestController::class, 'test'])->name('test')->middleware('admin');

// Create account
Route::post('/createAccount', [RegisterController::class, 'createAccount'])->name('createAccount')->middleware('guest');

// Activate account
Route::get('/activateAccount/{verificationCode}', [RegisterController::class, 'activateAccount'])->name('activateAccount')->middleware(['guest']);

// Initialize application
Route::get('/initializeApp', [AppController::class, 'initialize'])->name('initializeApp');

// Login
Route::post('/login', [LoginController::class, 'loginUser'])->name('loginUser')->middleware(['guest']);

// Logout
Route::get('/logout', [LoginController::class, 'logoutUser'])->name('logoutUser')->middleware('auth');

// Send password reset link
Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword'])->name('resetPassword')->middleware('guest');

// Update the password
Route::patch('/resetPasswordFinish', [ResetPasswordController::class, 'resetPasswordFinish'])->name('resetPasswordFinish')->middleware('guest');

// Initialize issues index page
Route::get('/initializeIssuesIndexPage', [IssueController::class, 'initializeIssuesIndexPage'])->name('initializeIssuesIndexPage')->middleware('auth');

// Initialize add issue page
Route::get('/initializeAddIssuePage', [IssueController::class, 'initializeAddIssuePage'])->name('initializeAddIssuePage')->middleware('auth');

// Load issues list
Route::post('/loadIssues', [IssueController::class, 'loadIssues'])->name('loadIssues')->middleware('auth');

// Add issue
Route::post('/issues/add', [IssueController::class, 'addIssue'])->name('addIssue')->middleware(['auth','nonAdmin']);
