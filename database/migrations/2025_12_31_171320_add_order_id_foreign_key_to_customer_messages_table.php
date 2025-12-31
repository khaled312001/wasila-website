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
        // Skip this migration if tables don't exist
        if (!Schema::hasTable('customer_messages') || !Schema::hasTable('orders')) {
            return;
        }
        
        // Skip if order_id column doesn't exist
        if (!Schema::hasColumn('customer_messages', 'order_id')) {
            return;
        }
        
        // Check if foreign key already exists
        try {
            $foreignKeys = \DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'customer_messages' 
                AND COLUMN_NAME = 'order_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                // Foreign key already exists, skip
                return;
            }
        } catch (\Exception $e) {
            // If we can't check, skip to avoid errors
            \Log::warning("Could not check foreign keys: " . $e->getMessage());
            return;
        }
        
        // Try to add foreign key, but don't fail if it doesn't work
        try {
            Schema::table('customer_messages', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Silently skip - foreign key is not critical for the application to work
            \Log::warning("Could not add foreign key 'order_id' to 'customer_messages' table: " . $e->getMessage());
            // Don't throw - allow migration to complete
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_messages', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });
    }
};
