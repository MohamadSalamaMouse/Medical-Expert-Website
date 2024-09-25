<?php

namespace App\Http\Controllers\API\Auth\Pharamcy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PharmacyRegisterController extends Controller
{
    //
    public function sign_up(Request $request)
    {
        // Validation rules with more specific requirements
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pharmacies,email',
            'pharmacy_id' => 'required|numeric|unique:pharmacies,pharmacy_id',
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
            $pharmacy = Pharmacy::create([
                'name' => $request->name,
                'email' => $request->email,
                'pharmacy_id' => $request->pharmacy_id,
                'password' => Hash::make($request->password)
            ]);

            // Create a personal access token for the doctor with appropriate role

            $pharmacy->notify(new EmailVerificationNotification());
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
