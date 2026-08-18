<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_import_booking', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('bill_booking');
            $table->string('container_size');
            $table->decimal('container_price', 10, 2);
            $table->date('pickup_date');
            $table->date('delivery_date');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->string('dropoff_location_link')->nullable();
            $table->string('document_holder_phone');
            $table->string('delivery_contact_phone');
            $table->string('status')->default('pending');
            $table->string('booking_code')->nullable();
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->json('cargo_list_file_urls')->nullable();
            $table->decimal('cargo_weight', 10, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('customer_full_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_import_booking');
    }
};