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
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.',

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
