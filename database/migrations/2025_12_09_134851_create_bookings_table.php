<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_date_id')->constrained('package_dates')->onDelete('cascade');

            $table->enum('room_type', ['double', 'triple', 'quad']);
            $table->decimal('total_price', 15, 2);

            $table->string('booking_code')->unique();
            $table->integer('jumlah_jamaah')->default(1);

            // Customer information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Address
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();

            // Emergency
            $table->string('nama_darurat')->nullable();
            $table->string('nomor_darurat')->nullable();

            // Status
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed'])->default('unpaid');

            // ⭐ MIDTRANS TOKEN
            $table->string('snap_token')->nullable();

            // Payment
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_date')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
