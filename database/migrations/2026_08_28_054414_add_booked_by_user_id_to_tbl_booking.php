<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            // Make customer_id nullable so admin-internal bookings don't require a customer
            $table->unsignedBigInteger('customer_id')->nullable()->change();
            // Track which internal user created this booking (admin/operation/accountant)
            $table->unsignedBigInteger('booked_by_user_id')->nullable()->after('customer_id');
            $table->foreign('booked_by_user_id')->references('user_id')->on('tbl_user')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->dropForeign(['booked_by_user_id']);
            $table->dropColumn('booked_by_user_id');
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
};
