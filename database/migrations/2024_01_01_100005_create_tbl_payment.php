<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 50)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('transaction_reference', 100)->nullable();
            $table->date('date')->nullable();
            $table->foreign('booking_id')->references('booking_id')->on('tbl_booking')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_payment');
    }
};
