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

    // jika ada beberapa setting yang berisi JSON, cast agar otomatis array/object
    protected $casts = [
        // 'value' => 'array' // jangan aktifkan global, kalau banyak entry non-json
    ];

    /** 
        * helper stactic untuk mengambil value cepat.
        * usage: LandingSetting::getValue('hero_title', 'default value');
    */

    public static function getValue($key, $default = null)
    {
        $record = static::where('key', $key)->first();
        if (!$record) {
            return $default;
        }
        
        // jika type json, decode
        if ($record->type === 'json') {
            $decode =  json_decode($record->value, true);
            return $decode ?? $default;
        }
        return $record->value ?? $default;
    }
    
    /** 
        * helper untuk set/insert update setting    
    */
    public static function setValue(string $key, $value, string $type = 'text')
    {
        $val = is_array($value) || is_object($value) ? json_encode($value) : $value;
        return static::updateOrCreate(['key' => $key], [
            'value' => $val, 
            'type' => $type
        ]);
    }
}
