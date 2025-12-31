<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Try to create table, but handle case where it already exists
        try {
            if (!Schema::hasTable('order_documentation')) {
                Schema::create('order_documentation', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('order_id');
                    $table->string('title');
                    $table->text('description')->nullable();
                    $table->string('video_path');
                    $table->string('thumbnail_path')->nullable();
                    $table->integer('duration')->nullable();
                    $table->bigInteger('file_size')->nullable();
                    $table->unsignedBigInteger('uploaded_by')->nullable();
                    $table->boolean('is_visible_to_customer')->default(true);
                    $table->timestamp('viewed_at')->nullable();
                    $table->timestamps();
                    $table->index('order_id');
                });
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Table already exists (error code 42S01 for MySQL)
            // Skip creation if table exists, otherwise re-throw
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Base table or view already exists') === false) {
                throw $e;
            }
        }
        
        // Add foreign keys separately if tables exist
        if (Schema::hasTable('order_documentation') && Schema::hasTable('orders')) {
            try {
                Schema::table('order_documentation', function (Blueprint $table) {
                    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // Foreign key already exists or creation failed - skip
            }
        }
        
        if (Schema::hasTable('order_documentation') && Schema::hasTable('admins')) {
            try {
                Schema::table('order_documentation', function (Blueprint $table) {
                    $table->foreign('uploaded_by')->references('id')->on('admins')->onDelete('set null');
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // Foreign key already exists or creation failed - skip
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_documentation');
    }
};

