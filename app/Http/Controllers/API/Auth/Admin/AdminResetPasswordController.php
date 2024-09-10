<?php

namespace App\Http\Controllers\API\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;

class AdminResetPasswordController extends Controller
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
        $admin = Admin::where('email', $request->email)->first();
        $admin->update(
            [
                'password' => Hash::make($request->password)
            ]
        );
        $admin->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',

        ], 200);
    }
}
