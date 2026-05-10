<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_driver', function (Blueprint $table) {
            $table->id('driver_id');
            $table->string('full_name', 100);
            $table->string('phone', 20)->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->unsignedBigInteger('assigned_truck')->nullable();
            $table->foreign('assigned_truck')->references('truck_id')->on('tbl_truck')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_driver');
    }
};
