<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('duration_days');
            $table->enum('type', ['reguler', 'plus_dubai', 'plus_turki', 'plus_jepang']);
            $table->decimal('double_price', 15, 2);
            $table->decimal('triple_price', 15, 2);
            $table->decimal('quad_price', 15, 2);
            $table->string('airline');
            $table->string('hotel_madinah');
            $table->string('hotel_makkah');
            $table->json('facilities')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};