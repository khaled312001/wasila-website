<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop table if exists (including foreign keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('contact_message_replies');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Check if contact_messages table exists
        if (!Schema::hasTable('contact_messages')) {
            throw new \Exception('contact_messages table does not exist. Please run migrations first.');
        }
        
        // Get the actual column type from contact_messages table
        $contactMessagesIdType = 'unsignedBigInteger'; // default
        try {
            $result = DB::select("SHOW COLUMNS FROM contact_messages WHERE Field = 'id'");
            if (!empty($result)) {
                $type = strtoupper($result[0]->Type);
                // Check if it's int or bigint
                if (strpos($type, 'INT(') !== false && strpos($type, 'BIGINT') === false) {
                    $contactMessagesIdType = 'unsignedInteger';
                }
            }
        } catch (\Exception $e) {
            // If we can't check, use default
        }
        
        Schema::create('contact_message_replies', function (Blueprint $table) use ($contactMessagesIdType) {
            $table->id();
            
            // Use the same type as contact_messages.id
            if ($contactMessagesIdType === 'unsignedInteger') {
                $table->unsignedInteger('contact_message_id');
            } else {
                $table->unsignedBigInteger('contact_message_id');
            }
            
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // image, video, audio, document
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('sender_type', ['admin', 'customer'])->default('admin');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        
        // Foreign keys will be added in a separate migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_message_replies');
    }
};
