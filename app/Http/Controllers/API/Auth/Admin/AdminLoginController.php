<?php

namespace App\Http\Controllers\API\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
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
        $admin = Admin::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
                'data' => null,
                'token' => null,
            ]);
        }

        // Generate an authentication token for the user (you can adjust the token scopes)
        $token = $admin->createToken('admin', ['role:admin'])->plainTextToken;

        // Return a success response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'admin' => $admin,
            'token' => $token
        ], 200);
    }
}
