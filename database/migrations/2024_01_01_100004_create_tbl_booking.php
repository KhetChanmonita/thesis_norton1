<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_booking', function (Blueprint $table) {
            $table->id('booking_id');
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->string('booking_type', 50)->nullable();
            $table->string('container_number', 50)->nullable();
            $table->string('pickup_location', 200)->nullable();
            $table->string('dropoff_location', 200)->nullable();
            $table->date('pick_up_date')->nullable();
            $table->date('drop_off_date')->nullable();
            $table->decimal('cargo_weight', 10, 2)->nullable();
            $table->date('booking_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 12, 2)->nullable();
            $table->foreign('schedule_id')->references('schedule_id')->on('tbl_schedule')->nullOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_booking');
    }
};
