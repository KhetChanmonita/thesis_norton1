<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tbl_user MODIFY role ENUM('admin','user','staff','accountant','driver') NOT NULL DEFAULT 'user'");

        Schema::table('tbl_user', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('role');
            $table->foreign('driver_id')->references('driver_id')->on('tbl_driver')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_user', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
        DB::statement("ALTER TABLE tbl_user MODIFY role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
