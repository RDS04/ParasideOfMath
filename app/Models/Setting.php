<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (Schema::hasTable('settings')) {
                $setting = static::where('key', $key)->first();
                if ($setting) {
                    return $setting->value;
                }
            }
        } catch (\Exception $e) {
            // Ignore DB exception, fallback to json
        }

        // Fallback to JSON storage file if DB fail
        $filePath = storage_path('app/settings.json');
        if (file_exists($filePath)) {
            $json = json_decode(file_get_contents($filePath), true);
            if (isset($json[$key])) {
                return $json[$key];
            }
        }

        return $default;
    }

    /**
     * Simpan nilai setting berdasarkan key.
     */
    public static function set(string $key, $value): void
    {
        $stringValue = is_bool($value) ? ($value ? '1' : '0') : (string) $value;

        try {
            if (Schema::hasTable('settings')) {
                static::updateOrCreate(
                    ['key' => $key],
                    ['value' => $stringValue]
                );
            }
        } catch (\Exception $e) {
            // Ignore DB exception
        }

        // Always save to JSON file as fallback/sync
        $filePath = storage_path('app/settings.json');
        $data = [];
        if (file_exists($filePath)) {
            $data = json_decode(file_get_contents($filePath), true) ?: [];
        }
        $data[$key] = $stringValue;
        @file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
