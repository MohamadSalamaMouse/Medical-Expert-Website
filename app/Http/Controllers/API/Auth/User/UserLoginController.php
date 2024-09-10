<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserLoginController extends Controller
{
    //
    public function login(Request $request)
    {
        // Validate the request data with custom error messages
        $request->validate([
            'SSN' => ['required', 'digits:14'],
            'password' => [
                'required' ],

        ], [
            'SSN.required' => 'The SSN field is required.',
            'SSN.digits' => 'The SSN must be exactly 14 digits.',
            'password.required' => 'The password field is required.']);

        // Find the user by SSN
        $user = User::where('SSN', $request->SSN)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
           return response()->json([
               'status' => 'error',
               'message' => 'Invalid SSN or password.',
               'data' => null,
               'token' => null
           ]);
        }

            // Check if the user is verified (assuming you're using email verification)
    if (!$user->email_verified_at) { // If you're using other verification, adjust this accordingly
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
            'customer' => $user,
            'token' => $user->createToken('user', ['role:user'])->plainTextToken
        ], 200);
    }
}
