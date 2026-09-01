<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->timestamp('driver_arrived_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->dropColumn('driver_arrived_at');
        });
    }
};
