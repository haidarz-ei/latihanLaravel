<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandingProgram extends Model
{
    use SoftDeletes;

    protected $table = 'landing_programs';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'position',
        'image',
        'status'
    ];


    protected $casts = [
        'position' => 'integer',
        'status' => 'boolean',
    ];

    // scoper helper
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }   

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('created_at', 'desc');
    }
}
