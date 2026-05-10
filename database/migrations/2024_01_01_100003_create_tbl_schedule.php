<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_schedule', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('driver_id');
            $table->string('location_truck', 200)->nullable();
            $table->date('date_of_truck_available')->nullable();
            $table->foreign('truck_id')->references('truck_id')->on('tbl_truck')->cascadeOnDelete();
            $table->foreign('driver_id')->references('driver_id')->on('tbl_driver')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_schedule');
    }
};
