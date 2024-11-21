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
            'email' => ['required', 'email'],
            'password' => [
                'required' ],

        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.']);

        // Find the user by SSN
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided email or password is incorrect.'],
            ]);
        }

        // Return a success response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'customer' => $user,
            'token' => $user->createToken('user', ['role:user'])->plainTextToken
        ], 200);
    }}
