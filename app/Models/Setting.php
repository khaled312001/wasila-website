<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        try {
            // Check if cache is available
            if (!function_exists('cache') || !class_exists('Illuminate\Support\Facades\Cache')) {
                // If cache is not available, get directly from database
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            }
            
            $cacheKey = "setting.{$key}";
            
            // Try to get from cache first
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            
            // Get from database
            $setting = static::where('key', $key)->first();
            $value = $setting ? $setting->value : $default;
            
            // Cache the value
            if ($value !== null) {
                try {
                    Cache::put($cacheKey, $value, 3600);
                } catch (\Exception $cacheException) {
                    // If caching fails, just continue without caching
                }
            }
            
            return $value;
        } catch (\Exception $e) {
            // If everything fails, just get from database without caching
            try {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            } catch (\Exception $dbException) {
                // If database also fails, return default
                return $default;
            }
        }
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAll()
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting.{$key}");
        }
        Cache::forget('settings.all');
    }
}
