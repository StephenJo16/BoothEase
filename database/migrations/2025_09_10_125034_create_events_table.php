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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('subdistrict_id')->nullable()->constrained('subdistricts')->onDelete('set null');
            $table->string('venue')->nullable()->comment('Venue name');
            $table->text('address')->nullable()->comment('Full formatted address');
            $table->dateTime('registration_deadline')->nullable();
            $table->boolean('refundable')->default(false);
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['draft', 'finalized', 'published', 'ongoing', 'completed'])->default('draft');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->integer('capacity')->nullable();
            $table->json('booth_configuration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
