<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_schedule', function (Blueprint $table) {
            $table->dropColumn('date_of_truck_available');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_schedule', function (Blueprint $table) {
            $table->date('date_of_truck_available')->nullable()->after('location_truck');
        });
    }
};
