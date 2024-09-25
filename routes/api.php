<?php

use App\Http\Controllers\API\Auth\Admin\AdminForgotPasswordController;
use App\Http\Controllers\API\Auth\Admin\AdminLoginController;
use App\Http\Controllers\API\Auth\Admin\AdminResetPasswordController;
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
Route::post('doctor/password/forgot', [DoctorForgotPasswordController::class, 'forgotPassword']);
Route::post('doctor/password/reset', [DoctorResetPasswordController::class, 'resetPassword']);
Route::post('doctor/verify', [DoctorEmailVerificationController::class, 'verify']);
Route::post('doctor/get-otp', [DoctorEmailVerificationController::class, 'sendOtp']);
//End Doctor Auth Routes

//Lab Auth Routes
Route::Post('lab/register', [LabRegisterController::class, 'sign_up']);
Route::Post('lab/login', [LabLoginController::class, 'login']);
Route::post('lab/password/forgot', [LabForgotPasswordController::class, 'forgotPassword']);
Route::post('lab/password/reset', [LabResetPasswordController::class, 'resetPassword']);
Route::post('lab/verify', [LabEmailVerificationController::class, 'verify']);
Route::post('lab/get-otp', [LabEmailVerificationController::class, 'sendOtp']);
//End Lab Auth Routes

//patient Auth Routes
Route::Post('patient/register', [UserRegisterController::class, 'sign_up']);
Route::Post('patient/login', [UserLoginController::class, 'login']);
Route::post('patient/password/forgot', [UserForgotPasswordController::class, 'forgotPassword']);
Route::post('patient/password/reset', [UserResetPasswordController::class, 'resetPassword']);
Route::post('patient/verify', [UserEmailVerificationController::class, 'verify']);
Route::post('patient/get-otp', [UserEmailVerificationController::class, 'sendOtp']);
//End patient Auth Routes


//pharmacy Auth Routes
Route::Post('pharmacy/register', [PharmacyRegisterController::class, 'sign_up']);
Route::Post('pharmacy/login', [PharmacyLoginController::class, 'login']);
Route::post('pharmacy/password/forgot', [PharamcyForgotPasswordController::class, 'forgotPassword']);
Route::post('pharmacy/password/reset', [PharamcyResetPasswordController::class, 'resetPassword']);
Route::post('pharmacy/verify', [PharamcyEmailVerificationController::class, 'verify']);
Route::post('pharmacy/get-otp', [PharamcyEmailVerificationController::class, 'sendOtp']);
//End pharmacy Auth Routes




//admin Auth Routes
Route::Post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/password/forgot', [AdminForgotPasswordController::class, 'forgotPassword']);
Route::post('admin/password/reset', [AdminResetPasswordController::class, 'resetPassword']);
//End admin Auth Routes








