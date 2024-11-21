<?php

namespace App\Http\Controllers\API\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Str;

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
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, symbol, and number.',
            'password_confirmation.same' => 'Password confirmation must match the password.',
        ]);

        $admin = Admin::where('email', $request->session()->get('email'))->first();
        if (!$admin) {
            return response()->json(['error' => 'Admin not found.'], 404);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        $admin->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully. Please login.',
        ], 200);

     }








    //  {
    //      // Validate the request inputs
    //      $request->validate([
    //          'email' => ['required', 'email'],
    //          'otp' => ['required', 'max:6'],
    //          'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'],
    //      ], [
    //          'otp.required' => 'OTP code is required.',
    //          'otp.max' => 'Invalid OTP code, it should be 6 characters long.',
    //          'password.required' => 'Password is required.',
    //          'password.min' => 'The password must be at least 8 characters.',
    //          'password.regex' => 'The password must contain an uppercase letter "A-Z", lowercase letter "a-z", symbols "e.g. @, #" and numbers "1-9".',
    //      ]);

    //      // Validate OTP
    //      $otp2 = $this->otp->validate($request->email, $request->otp);

    //      // Handle OTP validation result
    //      if (!$otp2->status) {
    //          // Check the reason for the OTP failure
    //          if ($otp2->message === 'OTP is incorrect') {
    //              return response()->json(['error' => 'OTP is incorrect.'], 401); // Wrong OTP
    //          } elseif ($otp2->message === 'OTP has expired') {
    //              return response()->json(['error' => 'OTP is invalid or expired.'], 401); // Expired OTP
    //          } else {
    //              return response()->json(['error' => 'Invalid OTP code.'], 401); // Other invalid OTP cases
    //          }
    //      }

    //      // Check if the user exists (Admin in this case)
    //      $admin = Admin::where('email', $request->email)->first();
    //      if (!$admin) {
    //          return response()->json(['error' => 'Admin not found.'], 404);
    //      }

    //      // Update the password
    //      $admin->update([
    //          'password' => Hash::make($request->password),
    //      ]);

    //      // Delete any existing tokens for the user
    //      $admin->tokens()->delete();

    //      // Return success response
    //      return response()->json([
    //          'status' => 'success',
    //          'message' => 'Password reset successfully. Please login.',
    //      ], 200);
    //  }
     }
