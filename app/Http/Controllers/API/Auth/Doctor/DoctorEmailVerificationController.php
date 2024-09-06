<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;


class DoctorEmailVerificationController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }

    //
    public function sendOtp(Request $request)
    {
        $request->user()->notify(new EmailVerificationNotification());
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully.',

        ], 200);
    }
    public function verify(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => ['required', 'email', 'exists:doctors,email'],
            'otp' => ['required', 'max:6'],

        ]);


        $otp2 = $this->otp->validate($request->email, $request->otp);
        if (!$otp2->status) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP is incorrect.',
            ], 422);
        }

        // Find the user by email
        $doctor = Doctor::where('email', $request->email)->first();
        $doctor->update(['email_verified_at' => now()]);
        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.',
            
        ], 200);
    }
}
