<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->string('cargo_list_file', 255)->nullable()->after('total_price');
            $table->enum('payment_status', ['unpaid', 'deposit_paid', 'fully_paid'])
                  ->default('unpaid')->after('cargo_list_file');
            $table->string('access_token', 64)->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->dropColumn(['cargo_list_file', 'payment_status', 'access_token']);
        });
    }
};
