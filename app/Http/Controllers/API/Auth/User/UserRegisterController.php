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
        //dd($request->all());
        // Validation rules with more specific requirements
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:29',
            'email' => 'required|string|email|max:255|unique:users,email',
            'user_id' => 'required|numeric|digits:14|unique:users,user_id',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',

            ],
            'user_doctor_id' => 'nullable|numeric|digits:14'
        ],[
                'name.required' => 'The name field is required.',
                'user_id.required' => 'The National Id field is required. Please enter your ID.',
                'user_id.digits' => 'The National Id must be exactly 14 digits.',
                'user_id.unique' => 'This National Id is already registered. Please use a different one.',
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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => $request->user_id,
                'password' => Hash::make($request->password),
                'user_doctor_id' => $request->user_doctor_id,
            ]);



            $user->notify(new EmailVerificationNotification());

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful and please verify your email for login.',

            ], 201);
        }
        catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
