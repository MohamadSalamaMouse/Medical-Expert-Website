<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class DoctorLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data with custom error messages
        $request->validate([
            'SSN' => ['required'],
            "email" => ['required', 'email'],
            'password' => [
                'required',

            ],

        ], [
            'SSN.required' => 'The SSN field is required.',
            'password.required' => 'The password field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',

        ]);

        // Find the user by SSN
        $doctor = Doctor::where('SSN', $request->SSN)
            ->where('email', $request->email)
            ->first();


        // Check if the user exists and the password is correct
        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid SSN or password.',
                'data' => null,
                'token' => null
            ], 401);
        }
        if (!$doctor->email_verified_at) { // If you're using other verification, adjust this accordingly
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is not verified. Please verify your account to log in.',
                'data' => null,
                'token' => null
            ], 401);
        }
        // Return a success response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'data' => $doctor,
            'token' => $doctor->createToken('doctor', ['role:doctor'])->plainTextToken
        ], 200);
    }
}
