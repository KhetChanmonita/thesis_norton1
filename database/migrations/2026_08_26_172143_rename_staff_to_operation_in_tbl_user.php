<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing staff records first
        DB::table('tbl_user')->where('role', 'staff')->update(['role' => 'operation']);

        // Update ENUM to replace staff with operation
        DB::statement("ALTER TABLE tbl_user MODIFY role ENUM('admin','user','operation','accountant','driver') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::table('tbl_user')->where('role', 'operation')->update(['role' => 'staff']);
        DB::statement("ALTER TABLE tbl_user MODIFY role ENUM('admin','user','staff','accountant','driver') NOT NULL DEFAULT 'user'");
    }
};
