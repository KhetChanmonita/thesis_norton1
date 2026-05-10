<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_expense', function (Blueprint $table) {
            $table->id('expense_id');
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('expense_type', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('expense_date')->nullable();
            $table->text('description')->nullable();
            $table->foreign('truck_id')->references('truck_id')->on('tbl_truck')->nullOnDelete();
            $table->foreign('driver_id')->references('driver_id')->on('tbl_driver')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_expense');
    }
};
