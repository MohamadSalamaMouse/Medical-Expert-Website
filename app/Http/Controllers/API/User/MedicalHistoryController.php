<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use APP\Models\MedicalHistory;

class MedicalHistoryController extends Controller
{
    public function addPastIllness($patientId, Request $request)
{
    $medicalHistory = MedicalHistory::where('user_id', $patientId)->first();

    if (!$medicalHistory) {
        // Create a new medical history entry if not found
        $medicalHistory = new MedicalHistory();
        $medicalHistory->user_id = $patientId;
    }

    $pastIllnesses = $medicalHistory->past_illnesses ? json_decode($medicalHistory->past_illnesses, true) : [];

    // Add new illness to the array
    $pastIllnesses[] = [
        'illness' => $request->input('illness'),
        'diagnosis_date' => $request->input('diagnosis_date'),
        'recovery_date' => $request->input('recovery_date')
    ];

    // Save updated array back to the database
    $medicalHistory->past_illnesses = json_encode($pastIllnesses);
    $medicalHistory->save();

    return response()->json(['message' => 'Past illness added successfully.']);
}

}
