<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            
            // Add foreign keys separately if tables exist
            if (Schema::hasTable('orders')) {
                try {
                    Schema::table('order_documentation', function (Blueprint $table) {
                        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                    });
                } catch (\Illuminate\Database\QueryException $e) {
                    // Foreign key constraint error - skip if it fails
                    // This can happen if data types don't match or table structure differs
                }
            }
            
            if (Schema::hasTable('admins')) {
                try {
                    Schema::table('order_documentation', function (Blueprint $table) {
                        $table->foreign('uploaded_by')->references('id')->on('admins')->onDelete('set null');
                    });
                } catch (\Illuminate\Database\QueryException $e) {
                    // Skip if foreign key creation fails
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_documentation');
    }
};

