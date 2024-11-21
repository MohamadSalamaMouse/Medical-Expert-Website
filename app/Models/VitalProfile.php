<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_SSN',
        'blood_pressure',
        'heart_rate',
        'weight',
        'height',
        'bmi',
        'temperature',
        'respiratory_rate',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class);
    }
}
