<?php

use App\Http\Controllers\API\Auth\Doctor\DoctorEmailVerificationController;
use App\Http\Controllers\API\Auth\Doctor\DoctorForgotPasswordController;
use App\Http\Controllers\API\Auth\Doctor\DoctorLoginController;
use App\Http\Controllers\API\Auth\Doctor\DoctorRegisterController;
use App\Http\Controllers\API\Auth\Doctor\DoctorResetPasswordController;
use App\Http\Controllers\API\Auth\Lab\LabEmailVerificationController;
use App\Http\Controllers\API\Auth\Lab\LabForgotPasswordController;
use App\Http\Controllers\API\Auth\Lab\LabLoginController;
use App\Http\Controllers\API\Auth\Lab\LabRegisterController;
use App\Http\Controllers\API\Auth\Lab\LabResetPasswordController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Doctor Auth Routes
Route::Post('doctor/register', [DoctorRegisterController::class, 'sign_up']);
Route::Post('doctor/login', [DoctorLoginController::class, 'login']);
Route::post('password/forgot', [DoctorForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [DoctorResetPasswordController::class, 'resetPassword']);
//End Doctor Auth Routes

//Lab Auth Routes
Route::Post('lab/register', [LabRegisterController::class, 'sign_up']);
Route::Post('lab/login', [LabLoginController::class, 'login']);
Route::post('password/forgot', [LabForgotPasswordController::class, 'forgotPassword']);
Route::post('password/reset', [LabResetPasswordController::class, 'resetPassword']);
//End Lab Auth Routes



//doctor routes
Route::middleware(['auth:sanctum', 'type.doctor'])->prefix('doctor')->group(function () {
    Route::post('/verify', [DoctorEmailVerificationController::class, 'verify']);
    Route::get('/get-otp', [DoctorEmailVerificationController::class, 'sendOtp']);
});
//End doctor routes

//lab routes
Route::middleware(['auth:sanctum', 'type.lab'])->prefix('lab')->group(function () {
    Route::post('/verify', [LabEmailVerificationController::class, 'verify']);
    Route::get('/get-otp', [LabEmailVerificationController::class, 'sendOtp']);
});
