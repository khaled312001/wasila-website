<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', config('app.name', 'وسيلة'));
    }

    /**
     * Get site description
     */
    public static function siteDescription()
    {
        return self::get('site_description', 'منصة وسيلة والخدمات');
    }

    /**
     * Get contact email
     */
    public static function contactEmail()
    {
        return self::get('contact_email', 'info@wasila.org');
    }

    /**
     * Get contact phone
     */
    public static function contactPhone()
    {
        return self::get('contact_phone', '+966 XX XXX XXXX');
    }

    /**
     * Get address
     */
    public static function address()
    {
        $locale = app()->getLocale();
        $addressKey = $locale === 'ar' ? 'address_ar' : 'address_en';
        $defaultAddress = $locale === 'ar' ? 'مكة المكرمة ، المملكة العربية السعودية' : 'Mecca, Kingdom of Saudi Arabia';
        
        // Get address from the correct key
        $address = self::get($addressKey);
        
        // If address_ar doesn't exist, try to get from 'address' key (for backward compatibility)
        if (!$address && $locale === 'ar') {
            $address = self::get('address', $defaultAddress);
        }
        
        // If address_en doesn't exist, try to get from 'address' key
        if (!$address && $locale === 'en') {
            $address = self::get('address', $defaultAddress);
        }
        
        return $address ?: $defaultAddress;
    }

    /**
     * Get logo
     */
    public static function logo()
    {
        return self::get('logo', 'logo-footer.png');
    }

    /**
     * Get all contact information
     */
    public static function contactInfo()
    {
        return [
            'email' => self::contactEmail(),
            'phone' => self::contactPhone(),
            'address' => self::address(),
        ];
    }

    /**
     * Get all site information
     */
    public static function siteInfo()
    {
        return [
            'name' => self::siteName(),
            'description' => self::siteDescription(),
            'logo' => self::logo(),
            'contact' => self::contactInfo(),
        ];
    }
}
