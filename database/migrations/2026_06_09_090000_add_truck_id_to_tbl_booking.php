<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->unsignedBigInteger('truck_id')->nullable()->after('schedule_id');
            $table->foreign('truck_id')->references('truck_id')->on('tbl_truck')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_booking', function (Blueprint $table) {
            $table->dropForeign(['truck_id']);
            $table->dropColumn('truck_id');
        });
    }
};
