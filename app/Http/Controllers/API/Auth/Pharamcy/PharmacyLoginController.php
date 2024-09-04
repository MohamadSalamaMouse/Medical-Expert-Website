<?php

namespace App\Http\Controllers\API\Auth\Pharamcy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class PharmacyLoginController extends Controller
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
        $pharmacy = Pharmacy::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$pharmacy || !Hash::check($request->password, $pharmacy->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided email or password is incorrect.'],
            ]);
        }

        // Generate an authentication token for the user (you can adjust the token scopes)
        $token = $pharmacy->createToken('pharmacy', ['role:pharmacy'])->plainTextToken;

        // Return a success response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'pharmacy' => $pharmacy,
            'token' => $token
        ], 200);
    }
}
