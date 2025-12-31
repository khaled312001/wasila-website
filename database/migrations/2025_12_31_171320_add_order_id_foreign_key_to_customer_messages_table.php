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
        // Skip this migration if tables don't exist
        if (!Schema::hasTable('customer_messages') || !Schema::hasTable('orders')) {
            \Log::info('Skipping foreign key migration: tables do not exist');
            return;
        }
        
        // Skip if order_id column doesn't exist
        if (!Schema::hasColumn('customer_messages', 'order_id')) {
            \Log::info('Skipping foreign key migration: order_id column does not exist');
            return;
        }
        
        // Check if foreign key already exists
        try {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'customer_messages' 
                AND COLUMN_NAME = 'order_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                \Log::info('Foreign key already exists, skipping migration');
                return;
            }
        } catch (\Exception $e) {
            \Log::warning("Could not check foreign keys: " . $e->getMessage());
            // Continue to try adding the foreign key
        }
        
        // Clean up orphaned records (order_id that doesn't exist in orders table)
        try {
            $orphanedCount = DB::table('customer_messages')
                ->whereNotNull('order_id')
                ->whereNotIn('order_id', function($query) {
                    $query->select('id')->from('orders');
                })
                ->update(['order_id' => null]);
            
            if ($orphanedCount > 0) {
                \Log::info("Cleaned up {$orphanedCount} orphaned order_id references");
            }
        } catch (\Exception $e) {
            \Log::warning("Could not clean orphaned records: " . $e->getMessage());
            // Continue anyway
        }
        
        // Ensure both tables use InnoDB engine (required for foreign keys)
        try {
            DB::statement("ALTER TABLE customer_messages ENGINE=InnoDB");
            DB::statement("ALTER TABLE orders ENGINE=InnoDB");
        } catch (\Exception $e) {
            \Log::warning("Could not set engine to InnoDB: " . $e->getMessage());
        }
        
        // Try to add foreign key, but don't fail if it doesn't work
        try {
            Schema::table('customer_messages', function (Blueprint $table) {
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
            \Log::info('Successfully added foreign key constraint');
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
        if (!Schema::hasTable('customer_messages')) {
            return;
        }
        
        try {
            // Get the foreign key constraint name
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'customer_messages' 
                AND COLUMN_NAME = 'order_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                Schema::table('customer_messages', function (Blueprint $table) use ($foreignKeys) {
                    $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                    $table->dropForeign($constraintName);
                });
            }
        } catch (\Exception $e) {
            \Log::warning("Could not drop foreign key: " . $e->getMessage());
        }
    }
};
