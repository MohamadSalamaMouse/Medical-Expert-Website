<?php

namespace App\Http\Controllers\API\Auth\Pharamcy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;

class PharamcyEmailVerificationController extends Controller
{
    //
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
            'email' => ['required', 'email', 'exists:pharamcies,email'],
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
        $pharamcy = Pharmacy::where('email', $request->email)->first();
        $pharamcy->update(['email_verified_at' => now()]);
        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.',

        ], 200);
    }
}
