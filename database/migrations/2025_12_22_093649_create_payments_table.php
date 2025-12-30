<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique(); 
            $table->string('payment_type');
            $table->integer('amount'); 
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('transaction_id')->nullable(); 
            $table->string('snap_token')->nullable();
            $table->text('payment_url')->nullable(); 
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};