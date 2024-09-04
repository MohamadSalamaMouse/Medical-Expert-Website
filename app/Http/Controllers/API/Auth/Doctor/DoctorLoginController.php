<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class DoctorLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data with custom error messages
        $request->validate([
            'SSN' => ['required'],
            "email" => ['required', 'email'],
            'password' => [
                'required',

            ],

        ], [
            'SSN.required' => 'The SSN field is required.',
            'password.required' => 'The password field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            
        ]);

        // Find the user by SSN
        $doctor = Doctor::where('SSN', $request->SSN)
            ->where('email', $request->email)
            ->first();


        // Check if the user exists and the password is correct
        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            throw ValidationException::withMessages([
                'SSNandEmail' => ['The provided SSN and email  or password is incorrect.'],
            ]);
        }

        // Return a success response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'doctor' => $doctor,
            'token' => $doctor->createToken('doctor', ['role:doctor'])->plainTextToken
        ], 200);
    }
}
