<?php

namespace App\Http\Controllers\API\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class AdminForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
        ]);

        // Find admin by email
        $admin = Admin::where('email', $request->email)->first();

        // Check if the admin exists
        if (!$admin) {
            return response()->json(['error' => 'Admin not found.'], 404);
        }

        // Send reset password notification
        $admin->notify(new ResetPasswordNotification());

        return response()->json(['success' => true, 'message' => 'Password reset OTP sent.'], 200);

    }
}
