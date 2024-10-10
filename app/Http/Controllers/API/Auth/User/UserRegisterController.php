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
            'name' => 'required|string|min:5|max:29',
            'email' => 'required|string|email|max:255|unique:users,email',
            'SSN' => 'required|numeric|digits:14|unique:users,SSN',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],
        ],[
                'name.required' => 'The name field is required.',
                'SSN.required' => 'The National Id field is required. Please enter your SSN.',
                'SSN.digits' => 'The National Id must be exactly 14 digits.',
                'SSN.unique' => 'This National Id is already registered. Please use a different one.',
                'email.required' => 'Email is required.',
                'email.email' => 'Invalid email format. The valid format is like “example@example.com”.',
                'email.unique' => 'This patient email already has an account, please log in.',
                'password.required' => 'The password field is required.',

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

            $user->notify(new EmailVerificationNotification());
            // Return success response with doctor and token
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful and please verify your email for login.',

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
