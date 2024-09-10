<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class UserRegisterController extends Controller
{
    public function sign_up(Request $request)
    {
        // Validation rules with more specific requirements
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'SSN' => 'required|numeric|unique:users,SSN',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],

        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a new doctor with hashed password
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'SSN' => $request->SSN,
                'password' => Hash::make($request->password)
            ]);

            // Create a personal access token for the doctor with appropriate role
            $token = $user->createToken('user', ['role:user'])->plainTextToken;
            $user->notify(new EmailVerificationNotification());
            // Return success response with doctor and token
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful.',
                'data' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
