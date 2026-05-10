<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_extra_charge', function (Blueprint $table) {
            $table->id('extra_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 12, 2);
            $table->text('reason')->nullable();
            $table->enum('client_response', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->date('date')->nullable();
            $table->foreign('booking_id')->references('booking_id')->on('tbl_booking')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_extra_charge');
    }
};
