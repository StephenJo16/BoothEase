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
            $table->string('account_holder_name')->after('reason');
            $table->string('bank_name')->after('account_holder_name');
            $table->string('account_number')->after('bank_name');
            $table->string('document')->nullable()->after('account_number');
            $table->decimal('refund_amount', 15, 2)->after('document');
            $table->decimal('processing_fee', 15, 2)->after('refund_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn([
                'account_holder_name',
                'bank_name',
                'account_number',
                'document',
                'refund_amount',
                'processing_fee'
            ]);
        });
    }
};
