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
        Schema::table('users', function (Blueprint $table) {
            // allow missing data from OAuth
            $table->string('phone_number')->nullable()->change();
            $table->string('business_category')->nullable()->change();
            // if "name" is your business name and is NOT NULL/UNIQUE, keep it required
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable(false)->change();
            $table->string('business_category')->nullable(false)->change();
            // $table->string('name')->nullable(false)->change();
        });
    }
};
