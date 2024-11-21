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

        $lab = Lab::where('email', $request->email)->first();

        if (!$lab) {
            return response()->json(['error' => 'Lab not found.'], 404);
        }

        $lab->notify(new ResetPasswordNotification());

        return response()->json(['success' => true, 'message' => 'Password reset code sent.'], 200);
    }
}
