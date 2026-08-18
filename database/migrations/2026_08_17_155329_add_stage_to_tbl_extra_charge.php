<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_extra_charge', function (Blueprint $table) {
            $table->enum('stage', ['first', 'second'])->default('first')->after('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_extra_charge', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }
};
