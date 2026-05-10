<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_truck', function (Blueprint $table) {
            $table->id('truck_id');
            $table->string('truck_name', 100);
            $table->string('truck_size', 50)->nullable();
            $table->string('truck_color', 50)->nullable();
            $table->string('truck_picture')->nullable();
            $table->string('plate_number', 20)->unique();
            $table->decimal('capacity_ton', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_truck');
    }
};
