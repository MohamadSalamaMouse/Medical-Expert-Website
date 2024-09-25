<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\EmailVerificationNotification;
use Ichtrojan\Otp\Otp;

class UserEmailVerificationController extends Controller
{
    //
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],


        ]);
        $user = User::where('email', $request->email)->first();
        $user->notify(new EmailVerificationNotification());
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully.',

        ], 200);
    }
    public function verify(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
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
        $user = User::where('email', $request->email)->first();
        $user->update(['email_verified_at' => now()]);
        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully.',

        ], 200);
    }
}
