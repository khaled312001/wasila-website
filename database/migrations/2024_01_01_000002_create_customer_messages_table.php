<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customer_messages')) {
            Schema::create('customer_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->text('message');
                $table->enum('sender_type', ['customer', 'admin'])->default('customer');
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_messages');
    }
};

