<?php

namespace App\Http\Controllers\API\Auth\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class LabForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
         ]   , [
                'email.required' => 'Email is required.',
                'email.email' => 'Invalid email format, the valid format is like “example@example.com”.',
            ]);


        $input = $request->only('email');
        $user = Lab::where('email', $input)->first();
        $user->notify(new ResetPasswordNotification());
        $success['succees'] = true;
        return response()->json($success, 200);
    }
}
