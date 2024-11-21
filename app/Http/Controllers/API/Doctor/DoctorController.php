<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\VitalProfile;
use Illuminate\Http\Request;
use App\Models\User;


class DoctorController extends Controller
{
    public function viewPatients(Request $request)
    {
        // Get the authenticated doctor
        $doctor = auth()->doctor();

        // Fetch the patients linked to this doctor
        $users = User::where('user_doctor_id', $doctor->doctor_id)
            ->orderBy('name') 
            ->get(['name', 'doctor_id', 'dob', 'gender', 'last_visit']);

        return response()->json($users, 200);
    }
    public function viewVitalProfile($user_id)
{
    // Get the authenticated doctor
    $doctor = auth()->user();

    // Find the patient
    $user = User::where('user_id', $user_id)->where('user_id', $doctor->doctor_id)->first();

    // If the patient is not found or does not belong to the doctor, return an error
    if (!$user) {
        return response()->json(['message' => 'Patient not found or you do not have access.'], 404);
    }

    // Retrieve the vital profile
    $vitalProfile = VitalProfile::where('user_id', $user->user_id)->first();

    // If the vital profile does not exist, return an error
    if (!$vitalProfile) {
        return response()->json(['message' => 'Vital profile not found.'], 404);
    }

    return response()->json($vitalProfile, 200);
}




public function searchUserById(Request $request)
{
    // Validate the input to ensure it's a 14-digit SSN
    $request->validate([
        'doctor_id' => 'required|digits:14',
    ]);

    // Get the authenticated doctor
    $doctor = auth()->user();

    // Find the user (patient) linked to this doctor by SSN
    $user = User::where('doctor_id', $doctor->doctor_id)
        ->where('doctor_id', $request->doctor_id)
        ->first();

    // If no user is found, return a 'no user found' message
    if (!$user) {
        return response()->json(['message' => 'No patient found with the entered ID.'], 404);
    }

    // Return the found user data
    return response()->json($user, 200);
}



public function filterPatients(Request $request)
{
    // Get the authenticated doctor
    $doctor = auth()->user();

    // Query the patients linked to the doctor
    $query = User::where('user_doctor_id', $doctor->doctor_id);

    // Apply age range filter
    if ($request->has('age_range')) {
        $ageRange = explode('-', $request->age_range);
        $minAge = $ageRange[0];
        $maxAge = isset($ageRange[1]) ? $ageRange[1] : 100; // If no max is set, use 100

        $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?', [$minAge, $maxAge]);
    }

    // Apply gender filter
    if ($request->has('gender')) {
        $query->where('gender', $request->gender);
    }

    // Apply chronic condition filter (assuming there's a column chronic_condition in users table)
    if ($request->has('chronic_condition')) {
        $query->where('chronic_condition', $request->chronic_condition);
    }

    // Apply recent visit date filter
    if ($request->has('recent_visit')) {
        $query->whereHas('visits', function ($q) {
            $q->where('visit_date', '>=', now()->subDays(30));
        });
    }

    // Execute the query and get the filtered list of patients
    $patients = $query->get();

    // If no patients are found, return a message
    if ($patients->isEmpty()) {
        return response()->json(['message' => 'No patients found matching the selected criteria.'], 404);
    }

    return response()->json($patients, 200);
}

public function viewPatientProfile($id)
{
    try {
        // Fetch the patient and their medical history
        $user = User::with('medicalHistory')->find($id);

        if (!$user) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Prepare the profile data
        $profile = [
            'name' => $user->name,
            'national_id' => $user->user_id,
            'dob' => $user->dob,
            'gender' => $user->gender,
            'contact' => $user->contact,
            'medical_history' => [
                'past_illnesses' => $user->medicalHistory->past_illnesses,
                'surgeries' => $user->medicalHistory->surgeries,
                'chronic_conditions' => $user->medicalHistory->chronic_conditions,
                'current_medications' => $user->medicalHistory->current_medications,
                'allergies' => $user->medicalHistory->allergies
            ],
            'vitals' => [
                'blood_pressure' => $user->blood_pressure,
                'heart_rate' => $user->heart_rate
            ]
        ];

        return response()->json($profile);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to load patient profile. Please try again later.'], 500);
    }
}

}




