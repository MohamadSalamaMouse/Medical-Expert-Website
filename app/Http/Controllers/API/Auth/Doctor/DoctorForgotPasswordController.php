<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class DoctorForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format, the valid format is like “example@example.com”.',


        ]);
        $input = $request->only('email');
        $user = Doctor::where('email', $input)->first();
        $user->notify(new ResetPasswordNotification());
        $success['succees'] = true;
        return response()->json($success, 200);
    }
}
