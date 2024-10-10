<?php

namespace App\Services;

use App\Models\User;
use Ichtrojan\Otp\Otp;

class OtpService
{
    public function validate($otp)
    {
        // Your OTP validation logic here
        $user = User::where('otp', $otp)->first();

        if ($user) {
            return (object) ['status' => true, 'user' => $user];
        }

        return (object) ['status' => false];
    }
//} -->


// namespace App\Services;

// use Ichtrojan\Otp\Otp;

// class OtpService
// {
//     protected $otp;

//     public function __construct()
//     {
//         $this->otp = new Otp();
//     }

//     public function generate($user)
//     {
//         $otpCode = $this->otp->generate($user->email); // Generate OTP using the user's email
//         // Store the OTP in the database or send it via email
//         // e.g., $user->otp = $otpCode; $user->save();
//         return $otpCode;
//     }

//     public function validate($otp, $user)
//     {
//         // Validate the OTP
//         if ($this->otp->verify($user->email, $otp)) {
//             // Optionally clear the OTP after validation
//             // $user->otp = null; $user->save();
//             return true;
//         }
//         return false;
//     }
}

