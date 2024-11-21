<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class UserForgotPasswordController extends Controller
{

        public function forgotPassword(Request $request)
        {
            // Validate the request input
            $request->validate([
                'email' => ['required', 'email'],
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Invalid email format, the valid format is like “example@example.com”.',
            ]);

            // Find user by email
            $user = User::where('email', $request->email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Send reset password notification
            $user->notify(new ResetPasswordNotification());

            // Return success response
            return response()->json(['success' => true, 'message' => 'Password reset OTP code sent.'], 200);
        }
    }


