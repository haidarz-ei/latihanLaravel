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
        'file_ktp',
        'file_kk',
        'file_ijazah',
        'file_selfie',
        'asal_sd',
        'asal_smp',
        'asal_sma',
        
        'provinsi',
        'kota_kab',
        'kecamatan',
        'kode_pos',
        'nama_ibu',
        'sumber_informasi',
        'alamat_domisili',
        'status',
    ];

    // relasi ke table users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
