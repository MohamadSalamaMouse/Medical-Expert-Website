<?php

namespace App\Http\Controllers\API\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Str;

class AdminResetPasswordController extends Controller
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
         ],
            [

                            'otp.required' => 'OTP code is required.',
                            'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
                            'password.required' => 'Password is required.',
                            'password.min' => 'The password must be at least 8 characters.',
                            'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',

    ]);
    $otp2 = $this->otp->validate($request->email, $request->otp);
    if (!$otp2->status) {
        return response()->json(['error' => $otp2], 401);
    }
    $admin = Admin::where('email', $request->email)->first();
    $admin->update(
        [
            'password' => Hash::make($request->password)
        ]
    );
    $admin->tokens()->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Password reset successfully. Please login.',

    ], 200);
}
}

//     public function resetPassword(Request $request)
//     {
//         // Validate only the OTP
//         $request->validate([
//             'otp' => ['required', 'max:6'],
//         ], [

//             'otp.required' => 'OTP code is required.',
//             'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
//             'password.required' => 'Password is required.',
//             'password.min' => 'The password must be at least 8 characters.',
//             'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',
//         ]);

//         // Validate the OTP

//         $otpStatus = $this->otp->validate($request->otp);
//         if (!$otpStatus->status) {
//             return response()->json(['error' => 'Invalid OTP. Please try again.'], 401);
//         }

//         // Find the admin associated with the OTP
//         $admin = Admin::where('otp', $request->otp)->first(); // Assuming you have stored OTPs against each admin

//         if (!$admin) {
//             return response()->json(['error' => 'Admin not found.'], 404);
//         }

//         // Generate a new random password
//         $newPassword = Str::random(10); // Generates a random 10-character password

//         // Update the admin's password and clear the OTP
//         $admin->update([
//             'password' => Hash::make($newPassword),
//             'otp' => null // Optionally clear the OTP field once used
//         ]);

//         // Revoke any existing tokens
//         $admin->tokens()->delete();

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Password reset successfully. Please use the new password to log in.',
//             'new_password' => $newPassword // Include the new password in the response
//         ], 200);
//     }
// }


