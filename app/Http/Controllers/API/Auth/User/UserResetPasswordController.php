<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;
use app\Services\OtpService;
use Illuminate\Support\Str;


class UserResetPasswordController extends Controller
{

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

        ]);
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if (!$otp2->status) {
            return response()->json(['error' => $otp2], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(
            [
                'password' => Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',

        ], 200);
    }
}


// public function resetPassword(Request $request)
// {
//     // Validate the OTP and new password
//     $request->validate([
//         'otp' => ['required', 'max:6'],
//         'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],
//     ],[

//             'otp.required' => 'OTP code is required.',
//             'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
//             'password.required' => 'Password is required.',
//             'password.min' => 'The password must be at least 8 characters.',
//             'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',

//     ]);

//     // Validate the OTP without requiring the email
//     $otpStatus = $this->otp->validate($request->otp);
//     if (!$otpStatus->status) {
//         return response()->json(['error' => 'Invalid OTP. Please try again.'], 401);
//     }

//     // Find the user associated with the OTP
//     $user = User::where('otp', $request->otp)->first(); // Assuming your User model has an 'otp' column

//     if (!$user) {
//         return response()->json(['error' => 'User not found.'], 404);
//     }

//     // Update the user's password
//     $user->update([
//         'password' => Hash::make($request->password),
//         'otp' => null // Optionally clear the OTP field after use
//     ]);

//     // Revoke any existing tokens
//     $user->tokens()->delete();

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Password reset successfully. Please login.',
//     ], 200);
// }

// }
