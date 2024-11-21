<?php

namespace App\Http\Controllers\API\Auth\Pharamcy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;

class PharamcyResetPasswordController extends Controller
{
    //
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'max:6'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],
        ], [

                'otp.required' => 'OTP code is required.',
                'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
                'password.required' => 'Password is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',

        ]);
       // Validate the OTP
        $otp2 = $this->otp->validate($request->email, $request->otp);

       // Custom handling of OTP validation response
        if (!$otp2->status) {
            // OTP has a status key, so check why it failed
            if ($otp2->message === 'OTP is incorrect') {
                return response()->json(['error' => 'OTP is incorrect.'], 401); // Mismatch or wrong OTP
            } elseif ($otp2->message === 'OTP has expired') {
                return response()->json(['error' => 'OTP is invalid or expired.'], 401); // Expired or invalid OTP
            } else {
                return response()->json(['error' => 'Invalid OTP code.'], 401); // General error if no clear message is provided
            }
        }

       // Check if the pharmacy exists
        $pharmacy = Pharmacy::where('email', $request->email)->first();
        if (!$pharmacy ) {
            return response()->json(['error' => 'Pharmacy not found.'], 404);
        }

        // Update the user's password
        $pharmacy ->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete any existing tokens for the user
        $pharmacy ->tokens()->delete();

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',
        ], 200);
    }
}
