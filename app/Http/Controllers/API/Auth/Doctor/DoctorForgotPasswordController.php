<?php

namespace App\Http\Controllers\API\Auth\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DoctorForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {
        $doctor = Doctor::where('email', $request->email)->first();

        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found.'], 404);
        }

        $doctor->notify(new ResetPasswordNotification());


        return response()->json(['success' => true, 'message' => 'Password reset OTP sent.'], 200);



    }

}
