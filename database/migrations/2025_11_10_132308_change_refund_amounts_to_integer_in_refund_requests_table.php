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
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('refund_amount')->change();
            $table->unsignedBigInteger('processing_fee')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->decimal('refund_amount', 15, 2)->change();
            $table->decimal('processing_fee', 15, 2)->change();
        });
    }
};
