<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkycRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto_ktp',
        'selfie_ktp', 
    ];

    // relasi ke table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
