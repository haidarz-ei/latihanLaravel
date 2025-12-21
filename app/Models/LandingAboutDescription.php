<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingAboutDescription extends Model
{
    use HasFactory;

    protected $table = 'landing_about_descriptions';

    protected $fillable = [
        'description',
        'position',
        'status',
    ];
}
