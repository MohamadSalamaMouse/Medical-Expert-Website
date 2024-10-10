<?php

namespace App\Http\Controllers\API\Auth\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LabLoginController extends Controller
{
    //
    public function login(Request $request)
    {
        // Validate the request with email and password
        $request->validate([
            'email' => ['required', 'email'],
            'password' => [
                'required',

            ],

        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format, the valid format is like “example@example.com”.',
            'password.required' => 'Password is required.',
            'password.min' => 'The password must be at least 8 characters.', // Custom message for min length


        ]);

        // Attempt to find the user by email
        $lab = Lab::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$lab || !Hash::check($request->password, $lab->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
                'data' => null,
                'token' => null
            ], 401);
        }

        // Generate an authentication token for the user (you can adjust the token scopes)
        $token = $lab->createToken('lab', ['role:lab'])->plainTextToken;

        if (!$lab->email_verified_at) { // If you're using other verification, adjust this accordingly
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
            'data' => $lab,
            'token' => $token
        ], 200);
    }
}
