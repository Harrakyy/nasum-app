<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('payment_type')->nullable();
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->json('payment_data')->nullable(); // Data response dari Midtrans
            $table->datetime('expired_at');
            $table->timestamps();
            
            $table->index(['order_id', 'status']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};