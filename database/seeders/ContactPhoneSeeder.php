<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add contact phone number to settings
        DB::table('settings')->updateOrInsert(
            ['key' => 'contact_phone'],
            [
                'key' => 'contact_phone',
                'value' => '+966559229980',
                'type' => 'text',
                'description' => 'رقم الهاتف للتواصل والواتساب',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Also add WhatsApp link
        DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp_link'],
            [
                'key' => 'whatsapp_link',
                'value' => 'https://wa.me/966559229980',
                'type' => 'url',
                'description' => 'رابط الواتساب للتواصل المباشر',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('Contact phone number and WhatsApp link added successfully!');
    }
}
