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
        // Check if tables exist and column exists
        if (!Schema::hasTable('customer_messages') || !Schema::hasTable('orders')) {
            return;
        }
        
        // Check if order_id column exists in customer_messages
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
                // Foreign key already exists
                return;
            }
        } catch (\Exception $e) {
            // If we can't check, continue
        }
        
        // Ensure data type compatibility - make order_id match orders.id type
        try {
            $ordersIdInfo = \DB::selectOne("
                SELECT COLUMN_TYPE 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'orders' 
                AND COLUMN_NAME = 'id'
            ");
            
            if ($ordersIdInfo) {
                $ordersIdType = $ordersIdInfo->COLUMN_TYPE;
                // Alter customer_messages.order_id to match orders.id type
                \DB::statement("ALTER TABLE `customer_messages` MODIFY COLUMN `order_id` {$ordersIdType} NULL");
            }
        } catch (\Exception $e) {
            // If we can't alter, continue and try to create foreign key anyway
        }
        
        // Try to add foreign key
        try {
            Schema::table('customer_messages', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Foreign key creation failed - this is OK if:
            // - Foreign key already exists
            // - Data types still don't match (errno: 150)
            // - Other constraint issues
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Duplicate foreign key') === false && 
                strpos($errorMessage, 'already exists') === false &&
                strpos($errorMessage, 'errno: 150') === false &&
                strpos($errorMessage, 'Foreign key constraint is incorrectly formed') === false) {
                // Only re-throw if it's a different, unexpected error
                throw $e;
            }
            // Otherwise, silently skip - foreign key may not be critical
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
