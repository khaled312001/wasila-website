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
        
        Schema::create('contact_message_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_message_id');
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
        
        // Add foreign keys after table creation using raw SQL
        // This allows MySQL to handle type conversion if needed
        try {
            DB::statement('ALTER TABLE contact_message_replies 
                ADD CONSTRAINT fk_contact_message_replies_contact_message_id 
                FOREIGN KEY (contact_message_id) REFERENCES contact_messages(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // If foreign key fails, try without constraint name
            DB::statement('ALTER TABLE contact_message_replies 
                ADD FOREIGN KEY (contact_message_id) REFERENCES contact_messages(id) ON DELETE CASCADE');
        }
        
        try {
            DB::statement('ALTER TABLE contact_message_replies 
                ADD CONSTRAINT fk_contact_message_replies_admin_id 
                FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL');
        } catch (\Exception $e) {
            // If foreign key fails, try without constraint name
            DB::statement('ALTER TABLE contact_message_replies 
                ADD FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_message_replies');
    }
};
