<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_payment', function (Blueprint $table) {
            $table->enum('payment_stage', ['first', 'second'])->nullable()->after('payment_method');
            $table->string('proof_file', 255)->nullable()->after('transaction_reference');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_payment', function (Blueprint $table) {
            $table->dropColumn(['payment_stage', 'proof_file']);
        });
    }
};
