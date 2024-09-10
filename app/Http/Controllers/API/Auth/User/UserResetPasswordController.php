<?php

namespace App\Http\Controllers\API\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;

class UserResetPasswordController extends Controller
{
    //
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'max:6'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],

        ]);
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if (!$otp2->status) {
            return response()->json(['error' => $otp2], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(
            [
                'password' => Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',

        ], 200);
    }
}
