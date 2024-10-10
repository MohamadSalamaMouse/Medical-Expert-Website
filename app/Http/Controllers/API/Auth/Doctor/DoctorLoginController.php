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
        $request->validate([
            'email' => ['required', 'email'],
            'password' => [
                'required',
            ],
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.', // Custom message for min length

        ]);

        // Attempt to find the user by email
        $doctor = Doctor::where('email', $request->email)->first();


        // Check if the user exists and the password is correct
        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
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
