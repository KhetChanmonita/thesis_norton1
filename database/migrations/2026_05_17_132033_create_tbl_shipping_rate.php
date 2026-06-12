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
        Schema::create('tbl_shipping_rate', function (Blueprint $table) {
            $table->id('rate_id');
            $table->enum('origin', ['sihanoukville', 'phnom_penh']);
            $table->string('province_name_km', 100);  // Khmer name
            $table->string('province_name_en', 100);  // English name
            $table->decimal('base_price', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['origin', 'province_name_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_shipping_rate');
    }
};
