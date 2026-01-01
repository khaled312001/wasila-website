<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('contact_message_replies');
        
        // Check if contact_messages table exists
        if (!Schema::hasTable('contact_messages')) {
            throw new \Exception('contact_messages table does not exist. Please run migrations first.');
        }
        
        Schema::create('contact_message_replies', function (Blueprint $table) {
            $table->id();
            
            // Use the same type as contact_messages.id
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
            
            // Add foreign keys after table creation
            $table->foreign('contact_message_id', 'fk_contact_message_replies_contact_message_id')
                  ->references('id')
                  ->on('contact_messages')
                  ->onDelete('cascade');
                  
            $table->foreign('admin_id', 'fk_contact_message_replies_admin_id')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_message_replies');
    }
};
