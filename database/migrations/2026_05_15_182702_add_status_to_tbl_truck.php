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
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->enum('status', ['available', 'delivering', 'maintenance'])
                  ->default('available')
                  ->after('capacity_ton');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
