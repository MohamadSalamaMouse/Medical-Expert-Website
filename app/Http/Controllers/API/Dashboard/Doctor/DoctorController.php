<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class DoctorController extends Controller
{
    public function viewPatients(Request $request)
    {
        // Get the authenticated doctor
        $doctor = auth()->doctor(); // Assuming you're using the User model for doctors

        // Fetch the patients linked to this doctor
        $users = User::where('doctor_id', $doctor->id)
            ->orderBy('name') // Default sorting by patient name
            ->get(['name', 'ssn', 'dob', 'gender', 'last_visit_date']);

        return response()->json($users, 200);
    }
}


