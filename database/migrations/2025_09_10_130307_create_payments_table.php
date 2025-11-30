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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('payment_method'); // e.g., credit_card, paypal, bank_transfer
            $table->string('payment_type')->nullable(); // e.g., gopay, bank_transfer, credit_card
            $table->string('payment_channel')->nullable(); // e.g., bca, mandiri, permata for virtual accounts
            $table->string('payment_status')->default('pending'); // e.g., pending, completed, failed
            $table->date('payment_date')->nullable();
            $table->integer('amount');
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
