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
        Schema::table('tbl_driver', function (Blueprint $table) {
            $table->string('driver_picture', 255)->nullable()->after('assigned_truck');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_driver', function (Blueprint $table) {
            $table->dropColumn('driver_picture');
        });
    }
};
