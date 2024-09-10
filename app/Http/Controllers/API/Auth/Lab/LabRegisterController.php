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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:labs,email',
            'lab_id' => 'required|numeric|unique:labs,lab_id',
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
            $lab = Lab::create([
                'name' => $request->name,
                'email' => $request->email,
                'lab_id' => $request->lab_id,
                'password' => Hash::make($request->password)
            ]);

            // Create a personal access token for the doctor with appropriate role
            $token = $lab->createToken('lab', ['role:lab'])->plainTextToken;
            $lab->notify(new EmailVerificationNotification());
            // Return success response with doctor and token
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful.',
                'data' => $lab,
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
