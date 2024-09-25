<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class DoctorRegisterController extends Controller
{
    /**
     * Handle the doctor registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sign_up(Request $request)
    {
        // Validation rules with more specific requirements
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'SSN' => 'required|string|max:255|unique:doctors,SSN',
            'email' => 'required|string|email|max:255|unique:doctors,email',
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
            $doctor = Doctor::create([
                'name' => $request->name,
                'email' => $request->email,
                'SSN' => $request->SSN,
                'password' => Hash::make($request->password)
            ]);

            // Create a personal access token for the doctor with appropriate role

            $doctor->notify(new EmailVerificationNotification());
            // Return success response with doctor and token
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful please verify your email for login.',

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
