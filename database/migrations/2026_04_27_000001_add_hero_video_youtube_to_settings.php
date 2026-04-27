<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['key' => 'hero_video_type', 'value' => 'file', 'type' => 'text', 'description' => 'نوع فيديو الهيرو: file أو youtube'],
            ['key' => 'hero_video_youtube', 'value' => '', 'type' => 'url', 'description' => 'رابط فيديو يوتيوب للهيرو'],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->updateOrInsert(['key' => $row['key']], $row);
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['hero_video_type', 'hero_video_youtube'])->delete();
    }
};
