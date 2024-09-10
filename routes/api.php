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
use App\Http\Controllers\API\Auth\Pharamcy\PharamcyEmailVerificationController;
use App\Http\Controllers\API\Auth\Pharamcy\PharamcyForgotPasswordController;
use App\Http\Controllers\API\Auth\Pharamcy\PharamcyResetPasswordController;
use App\Http\Controllers\API\Auth\Pharamcy\PharmacyLoginController;
use App\Http\Controllers\API\Auth\Pharamcy\PharmacyRegisterController;
use App\Http\Controllers\API\Auth\Pharmacy\PharmacyEmailVerificationController;
use App\Http\Controllers\API\Auth\User\UserEmailVerificationController;
use App\Http\Controllers\API\Auth\User\UserForgotPasswordController;
use App\Http\Controllers\API\Auth\User\UserLoginController;
use App\Http\Controllers\API\Auth\User\UserRegisterController;
use App\Http\Controllers\API\Auth\User\UserResetPasswordController;
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
Route::post('docotor/password/forgot', [DoctorForgotPasswordController::class, 'forgotPassword']);
Route::post('docotor/password/reset', [DoctorResetPasswordController::class, 'resetPassword']);
//End Doctor Auth Routes

//Lab Auth Routes
Route::Post('lab/register', [LabRegisterController::class, 'sign_up']);
Route::Post('lab/login', [LabLoginController::class, 'login']);
Route::post('lab/password/forgot', [LabForgotPasswordController::class, 'forgotPassword']);
Route::post('lab/password/reset', [LabResetPasswordController::class, 'resetPassword']);
//End Lab Auth Routes

//patient Auth Routes
Route::Post('patient/register', [UserRegisterController::class, 'sign_up']);
Route::Post('patient/login', [UserLoginController::class, 'login']);
Route::post('patient/password/forgot', [UserForgotPasswordController::class, 'forgotPassword']);
Route::post('patient/password/reset', [UserResetPasswordController::class, 'resetPassword']);
//End patient Auth Routes

//pharmacy Auth Routes

Route::Post('pharmacy/register', [PharmacyRegisterController::class, 'sign_up']);
Route::Post('pharmacy/login', [PharmacyLoginController::class, 'login']);
Route::post('pharmacy/password/forgot', [PharamcyForgotPasswordController::class, 'forgotPassword']);
Route::post('pharmacy/password/reset', [PharamcyResetPasswordController::class, 'resetPassword']);

//End pharmacy Auth Routes


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


//lab user routes
Route::middleware(['auth:sanctum', 'type.user'])->prefix('patient')->group(function () {
    Route::post('/verify', [UserEmailVerificationController::class, 'verify']);
    Route::get('/get-otp', [UserEmailVerificationController::class, 'sendOtp']);
});


//pharmacy user routes
Route::middleware(['auth:sanctum', 'type.pharmacy'])->prefix('pharmacy')->group(function () {
    Route::post('/verify', [PharmacyEmailVerificationController::class, 'verify']);
    Route::get('/get-otp', [PharamcyEmailVerificationController::class, 'sendOtp']);
});
