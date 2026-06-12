<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_shipping_rate', function (Blueprint $table) {
            $table->enum('type', ['import', 'export'])->default('import')->after('rate_id');
        });

        DB::table('tbl_shipping_rate')->update(['type' => 'import']);

        Schema::table('tbl_shipping_rate', function (Blueprint $table) {
            $table->dropUnique('tbl_shipping_rate_origin_province_name_en_unique');
            $table->unique(['type', 'origin', 'province_name_en'], 'shipping_rate_type_origin_province_unique');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_shipping_rate', function (Blueprint $table) {
            $table->dropUnique('shipping_rate_type_origin_province_unique');
            $table->unique(['origin', 'province_name_en'], 'tbl_shipping_rate_origin_province_name_en_unique');
            $table->dropColumn('type');
        });
    }
};
