<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;

class DoctorResetPasswordController extends Controller
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
    ], [

                'otp.required' => 'The OTP field is required.',
                'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',



    ]);
    $otp2 = $this->otp->validate($request->email, $request->otp);
    if (!$otp2->status) {
        return response()->json(['error' => $otp2], 401);
    }
    $doctor = Doctor::where('email', $request->email)->first();
    $doctor->update(
        [
            'password' => Hash::make($request->password)
        ]
    );
    $doctor->tokens()->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Password reset successfully. Please login.',

    ], 200);
}
}
//     public function resetPassword(Request $request)
// {
//     // Validate the request data
//     $request->validate([
//         'otp' => ['required', 'max:6'], // Validate only OTP
//         'password' => [ 'required',
//         'string',
//         'min:8',
//         'confirmed',
//         'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',], // Include password confirmation
//     ], [

//         'otp.required' => 'The OTP field is required.',
//         'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
//         'password.required' => 'The password field is required.',
//         'password.min' => 'The password must be at least 8 characters.',
//         'password.confirmed' => 'The password confirmation does not match.',
//         'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',

//     ]);

//     // Validate OTP
//     $otpStatus = $this->otp->validate($request->otp);
//     if (!$otpStatus->status) {
//         return response()->json(['error' => 'Invalid OTP. Please try again.'], 401);
//     }

//     // Find the user associated with the OTP
//     $doctor = Doctor::where('otp', $request->otp)->first();

//     if (!$doctor) {
//         return response()->json(['error' => 'User not found.'], 404);
//     }

//     // Update the password
//     $doctor->update([
//         'password' => Hash::make($request->password),
//         'otp' => null // Optionally clear the OTP field once used
//     ]);

//     // Revoke any existing tokens
//     $doctor->tokens()->delete();

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Password reset successfully. Please login again.',
//     ], 200);
// }


