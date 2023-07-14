<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\GoogleSignUpController;
use App\Http\Controllers\API\Auth\AppleSignUpController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Normal Sign-up
Route::post('/register', [RegisterController::class, 'register']);

// Google Sign-up
Route::post('/google/signup', [GoogleSignUpController::class, 'signUp']);

// Apple Sign-up
Route::post('apple/signup', [AppleSignUpController::class, 'signUp']);
Route::get('apple/callback', [AppleSignUpController::class, 'callback']);


// Normal login
Route::post('login', [LoginController::class, 'login']);

// Google sign-in
Route::get('google/login', [LoginController::class, 'redirectToGoogle']);
Route::get('google/callback', [LoginController::class, 'handleGoogleCallback']);

// Apple sign-in
Route::get('apple/login', [LoginController::class, 'redirectToApple']);
Route::get('apple/callback', [LoginController::class, 'handleAppleCallback']);

Route::post('forgot-password', [ResetPasswordController::class, 'sendResetCode']);
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);

Route::get('user', [UserController::class, 'show'])->middleware('auth:api');
Route::post('logout', [LogoutController::class, 'logout'])->middleware('auth:api');