<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DoctorLoginController extends Controller
{
    public function login(Request $request)
    {

        // Validate request
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'The email field is required.',
        'email.email' => 'Please provide a valid email address.',
        'password.required' => 'The password field is required.',
    ]);

    // Find the doctor by email
    $doctor = Doctor::where('email', $request->email)->first();

    // Check if the doctor exists and the password matches
    if (!$doctor || !Hash::check($request->password, $doctor->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.',
            'data' => null,
            'token' => null
        ], 401);
    }

    // Check if the doctor is verified
    if (!$doctor->email_verified_at) {
        return response()->json([
            'status' => 'error',
            'message' => 'Your account is not verified.',
            'data' => null,
            'token' => null
        ], 401);
    }

    // Check for Remember Me functionality
    if ($request->has('remember') && $request->remember == true) {
        // Store the token in cache for long-lived session
        $token = $doctor->createToken('doctor', ['role:doctor'])->plainTextToken;
        Cache::put('doctor_' . $doctor->doctor_id, $token, now()->addDays(30)); // Store for 30 days
    }

    // Return the success response with user details and token
    return response()->json([
        'status' => 'success',
        'message' => 'Login successful.',
        'data' => $doctor,
        'token' => $token
    ], 200);

    }



    public function authenticateWithRememberMe(Request $request)
    {
        // Retrieve the cached token based on doctor_id
        $cachedToken = Cache::get('doctor_' . $request->doctor_id);

        if ($cachedToken) {
            // Return the cached token if it exists
            return response()->json([
                'status' => 'success',
                'message' => 'Token retrieved from cache.',
                'token' => $cachedToken
            ], 200);
        }

        // Return an error if the token is not found or has expired
        return response()->json([
            'status' => 'error',
            'message' => 'Token expired or not found.'
        ], 401);
    }
//         $request->validate([
//             'email' => ['required', 'email'],
//             'password' => [
//                 'required',
//             ],
//         ], [
//             'email.required' => 'The email field is required.',
//             'email.email' => 'Please provide a valid email address.',
//             'password.required' => 'The password field is required.',
//             'password.min' => 'The password must be at least 8 characters.', // Custom message for min length

//         ]);





//         // Attempt to find the user by email
//         $doctor = Doctor::where('email', $request->email)->first();


//         // Check if the user exists and the password is correct
//         if (!$doctor || !Hash::check($request->password, $doctor->password)) {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'Invalid email or password.',
//                 'data' => null,
//                 'token' => null
//             ], 401);
//         }
//         if (!$doctor->email_verified_at) { // If you're using other verification, adjust this accordingly
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'Your account is not verified. Please verify your account to log in.',
//                 'data' => null,
//                 'token' => null
//             ], 401);
//         }
//         // Return a success response with user details and token
//         return response()->json([
//             'status' => 'success',
//             'message' => 'Login successful.',
//             'data' => $doctor,
//             'token' => $doctor->createToken('doctor', ['role:doctor'])->plainTextToken
//         ], 200);
//     }
// }

    }
