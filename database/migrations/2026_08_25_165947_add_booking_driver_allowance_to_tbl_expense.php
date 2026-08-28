<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_expense', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->after('driver_id');
            $table->decimal('driver_allowance', 12, 2)->nullable()->after('amount');
            $table->foreign('booking_id')->references('booking_id')->on('tbl_booking')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_expense', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['booking_id', 'driver_allowance']);
        });
    }
};
