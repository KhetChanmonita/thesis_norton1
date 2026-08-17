<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_cost_sheet', function (Blueprint $table) {
            $table->id('cost_sheet_id');
            $table->unsignedBigInteger('booking_id')->unique();
            $table->string('route', 100)->nullable();
            $table->string('size', 20)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('lolo', 12, 2)->default(0);
            $table->decimal('over_weight', 12, 2)->default(0);
            $table->decimal('express_way', 12, 2)->default(0);
            $table->decimal('extra', 12, 2)->default(0);
            $table->decimal('empty_return', 12, 2)->default(0);
            $table->decimal('standby_truck', 12, 2)->default(0);
            $table->decimal('repair', 12, 2)->default(0);
            $table->foreign('booking_id')->references('booking_id')->on('tbl_booking')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_cost_sheet');
    }
};
