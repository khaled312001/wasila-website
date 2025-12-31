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
        // Try to add foreign key, ignore if it already exists
        try {
            Schema::table('customer_messages', function (Blueprint $table) {
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Foreign key already exists, skip
            if (strpos($e->getMessage(), 'Duplicate foreign key') === false && 
                strpos($e->getMessage(), 'already exists') === false) {
                throw $e; // Re-throw if it's a different error
            }
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
