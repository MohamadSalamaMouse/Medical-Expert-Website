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

    //         $request->validate([
    //             'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])/'],
    //         ], [
    //             'password.required' => 'Password is required.',
    //             'password.min' => 'The password must be at least 8 characters.',
    //             'password.confirmed' => 'Password confirmation does not match.',
    //             'password.regex' => 'The password must contain an uppercase letter, lowercase letter, number, and symbol.',
    //         ]);

    //         $email = $request->session()->get('email'); // Retrieve the email stored in session
    //         $doctor = Doctor::where('email', $email)->first();

    //         if (!$doctor) {
    //             return response()->json(['error' => 'Doctor not found.'], 404);
    //         }

    //         // Update the doctor's password
    //         $doctor->update(['password' => Hash::make($request->password)]);

    //         // Optionally, delete any existing tokens
    //         $doctor->tokens()->delete();

    //         return response()->json(['message' => 'Password reset successfully. Please login.'], 200);
    //     }

        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'max:6'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],
        ],[

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

        // Check if the user exists
        $doctor = Doctor::where('email', $request->email)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found.'], 404);
        }

        // Update the user's password
        $doctor->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete any existing tokens for the doctor
        $doctor->tokens()->delete();

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',
        ], 200);
    }
}

