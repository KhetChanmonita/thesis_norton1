<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->enum('truck_location', ['shv', 'pp', 'both'])->default('both')->after('capacity_ton');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->dropColumn('truck_location');
        });
    }
};
