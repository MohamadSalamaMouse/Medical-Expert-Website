<?php

namespace App\Http\Controllers\Api\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;

class AdminVerifyOtpController extends Controller
{

    private $otp;

    public function __construct()
    {
        $this->otp = new Otp(); // Initialize Otp instance
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'max:6'],
        ], [
            'otp.required' => 'OTP code is required.',
            'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
        ]);

        // Use $this->otp for OTP validation
        $otpValidation = $this->otp->validate($request->session()->get('email'), $request->otp);

        if (!$otpValidation->status) {
            return response()->json(['error' => $otpValidation->message], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified. Proceed to reset password.',
        ], 200);
    }

}
