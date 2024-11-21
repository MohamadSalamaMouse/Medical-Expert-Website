<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\Doctor as Authenticatable;

class Doctor extends Model
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    protected $primaryKey = 'doctor_id';
    public $incrementing = false; 
    protected $keyType = 'bigInteger'; // Set the data type to match your database column type

    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
