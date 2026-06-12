<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->string('dropoff_location_link', 500)->nullable()->after('dropoff_location');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->dropColumn('dropoff_location_link');
        });
    }
};
