<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vital_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_vital')->unsigned();  // Same type as user.SSN
            $table->foreign('user_vital')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('blood_pressure');
            $table->integer('heart_rate');
            $table->float('weight');
            $table->float('height');
            $table->float('bmi');
            $table->float('temperature');
            $table->integer('respiratory_rate');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_profiles');
    }
};
