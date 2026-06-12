<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tbl_truck MODIFY status ENUM('available','delivering','maintenance','in_progress') DEFAULT 'available'");
    }

    public function down(): void
    {
        DB::statement("UPDATE tbl_truck SET status = 'delivering' WHERE status = 'in_progress'");
        DB::statement("ALTER TABLE tbl_truck MODIFY status ENUM('available','delivering','maintenance') DEFAULT 'available'");
    }
};
