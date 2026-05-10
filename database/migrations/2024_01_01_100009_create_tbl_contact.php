<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_contact', function (Blueprint $table) {
            $table->id('contact_id');
            $table->string('full_name', 100);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('company_name', 150)->nullable();
            $table->enum('inquiry_type', [
                'import', 'export', 'price', 'partnership', 'other'
            ])->default('other');
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_contact');
    }
};
