<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    protected $table = 'landing_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'status'
    ];

    public static function getValue($key, $default = null)
    {
        $data = static::where('key', $key)->where('status', 1)->first();
        return $data ? $data->value : $default;
    }
}
