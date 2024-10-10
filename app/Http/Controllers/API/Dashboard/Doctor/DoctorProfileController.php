<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;


class DoctorProfileController extends Controller
{

     // Method to set the profile
     public function storeProfile(Request $request)
     {
         // Validate the input fields
         $request->validate([
             'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
             'name' => 'required|string|min:4|max:30',
             'phone' => 'nullable|numeric|digits:11|starts_with:0',
             'email' => 'required|email|unique:users,email,' . auth()->id(),
             'dob' => 'nullable|date',
             'gender' => 'nullable|string|in:Male,Female,Other',
             'specialty' => 'required|string',
         ], [
             'photo.max' => 'This image is large, please upload an image with the max size of 5MB',
             'phone.digits' => 'Please enter a valid phone number. The valid phone number consists of 11 numbers',
             'specialty.required' => 'Please select your specialty',
         ]);

         // Get the authenticated doctor
         $doctor = auth()->doctor();

         // If a photo is uploaded, handle the file upload
         if ($request->hasFile('photo')) {
             $path = $request->file('photo')->store('doctor_photos', 'public');
             $doctor->photo = $path;
         }


         // Update the doctor's profile information
         $doctor->update([
             'name' => $request->name,
             'phone' => $request->phone,
             'email' => $request->email,
             'dob' => $request->dob,
             'gender' => $request->gender,
             'specialty' => $request->specialty,
         ]);

         return response()->json([
             'message' => 'Profile setup successfully.',
             'doctor' => $doctor,
         ], 200);
     }

     public function showProfile()
    {
        $doctor = auth()->doctor();
        return response()->json($doctor);
    }
     public function updateProfile(Request $request)
     {
         $request->validate([
             'photo' => 'nullable|mimes:jpg,jpeg,png|max:5120',
             'name' => 'required|string|max:255',
             'phone' => 'nullable|numeric|digits:11|starts_with:0',
             'email' => 'required|email',
             'dob' => 'nullable|date',
             'gender' => 'nullable|string|in:Male,Female,Other',
             'specialty' => 'required|string',
         ], [
             'photo.max' => 'This image is large, please upload an image with the max size of 5MB.',
             'phone.digits' => 'Please enter a valid phone number. The valid phone number consists of 11 numbers.',
             'specialty.required' => 'Please select your specialty.',
         ]);

         // Save the profile data logic
         // Assuming you have a Doctor model linked to the authenticated user
         $doctor = auth()->doctor();
         $doctor->update([
             'name' => $request->name,
             'phone' => $request->phone,
             'email' => $request->email,
             'dob' => $request->dob,
             'gender' => $request->gender,
             'specialty' => $request->specialty,
         ]);

         // Handle photo upload if provided
         if ($request->hasFile('photo')) {
             $path = $request->file('photo')->store('doctor_photos', 'public');
             $doctor->photo = $path;
             $doctor->save();
         }

         return back()->with('success', 'Profile updated successfully.');
     }
 }






