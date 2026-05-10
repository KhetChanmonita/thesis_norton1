<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_booking_status_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('total_price', 12, 2)->nullable();
            $table->date('completed_date')->nullable();
            $table->foreign('booking_id')->references('booking_id')->on('tbl_booking')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_booking_status_history');
    }
};
