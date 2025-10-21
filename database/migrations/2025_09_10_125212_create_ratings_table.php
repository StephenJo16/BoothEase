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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade'); // User who gives the rating
            $table->foreignId('ratee_id')->constrained('users')->onDelete('cascade'); // User who receives the rating (event organizer)
            $table->integer('rating'); // 1 to 5 stars
            $table->text('feedback')->nullable(); // Optional comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
