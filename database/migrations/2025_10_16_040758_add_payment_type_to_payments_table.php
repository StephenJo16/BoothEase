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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type')->nullable()->after('payment_method'); // e.g., gopay, bank_transfer, credit_card
            $table->string('payment_channel')->nullable()->after('payment_type'); // e.g., bca, mandiri, permata for virtual accounts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'payment_channel']);
        });
    }
};
