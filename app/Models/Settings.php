<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  Setting keys are predefined in the SettingsKeys enum
 *  To add a new setting, add the key to the enum and add the setting to the SettingsConfig class
 *  Settings are populated to the database using the SettingsSeeder
 * 
 * */

class Settings extends Model
{
    /** @use HasFactory<\Database\Factories\SettingsFactory> */
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'key';

    protected $fillable = ['value'];

    protected $casts = [
        'value' => 'json',
    ];

    // Values are stored as json in the database since they can be of any type
    // so we need to decode them before returning them
    public function getValueAttribute()
    {
        $decodedArray = json_decode($this->attributes['value'], true);
        return $decodedArray['value'];
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode(['value' => $value]); 
    }
}
