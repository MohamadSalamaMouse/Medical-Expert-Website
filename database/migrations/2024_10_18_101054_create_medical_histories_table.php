<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('medical_histories', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('user_id')->constrained('users'); // Links to the patient
//             $table->json('past_illnesses')->nullable(); // Store illnesses in JSON format
//             $table->json('surgeries')->nullable(); // Store surgeries in JSON format
//             $table->json('chronic_conditions')->nullable(); // Store chronic conditions in JSON format
//             $table->json('current_medications')->nullable(); // Store medications in JSON format
//             $table->json('allergies')->nullable(); // Store allergies in JSON format
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('medical_histories');
//     }
// };
