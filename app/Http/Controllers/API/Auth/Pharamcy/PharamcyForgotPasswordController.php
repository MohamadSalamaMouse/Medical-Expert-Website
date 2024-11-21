<?php

namespace App\Http\Controllers\API\Auth\Pharamcy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class PharamcyForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {

        $pharmacy = Pharmacy::where('email', $request->email)->first();

        if (!$pharmacy) {
            return response()->json(['error' => 'Pharmacy not found.'], 404);
        }

        $pharmacy->notify(new ResetPasswordNotification());

        return response()->json(['success' => true, 'message' => 'Password reset code sent.'], 200);
    }
}
