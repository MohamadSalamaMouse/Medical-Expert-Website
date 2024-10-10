<?php

namespace App\Http\Controllers\API\Auth\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class LabRegisterController extends Controller
{
    //

    public function sign_up(Request $request)
    {
        // Validation rules with more specific requirements
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:29',
            'email' => 'required|string|email|max:255|unique:labs,email',
            'lab_id' => 'required|numeric|unique:labs,lab_id',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],

            'password_confirmation' => 'required|string|same:password', // Ensure confirmation matches
    ], [
        'name.required' => 'Name is required.',
        'name.min' => 'The name must be more than 4 letters and less than 30 letters.',
        'name.max' => 'The name must be more than 4 letters and less than 30 letters.',

        'email.required' => 'Email is required.',
        'email.email' => 'Invalid email format, the valid format is like “example@example.com”.',
        'email.unique' => 'This patient email already has an account, please log in.',

        'lab_id.required' => 'Syndicate ID is required.',
        'lab_id.numeric' => 'Invalid Syndicate ID, please check the format.',
        'lab_id.unique' => 'This Syndicate ID is not registered, please try again.',

        'password.required' => 'Password is required.',
        'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #", and numbers "1-9".',

        'password_confirmation.required' => 'Password confirmation is required.',
        'password_confirmation.same' => 'The confirm password must match the entered one.',
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
            $lab = Lab::create([
                'name' => $request->name,
                'email' => $request->email,
                'lab_id' => $request->lab_id,
                'password' => Hash::make($request->password)
            ]);

            // Create a personal access token for the doctor with appropriate role

            $lab->notify(new EmailVerificationNotification());
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
