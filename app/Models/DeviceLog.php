<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_code',
        'device_type',
        'brand_model',
        'browser',
        'platform',
        'user_agent',
        'screen',
        'viewport',
        'dpr',
        'language',
        'online_status',
        'page',
        'ip',
        'city',
        'region',
        'country',
        'org',
        'lat',
        'lng',
        'maps_url',
    ];
}
