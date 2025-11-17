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
        Schema::create('event_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('floor_number')->default(1);
            $table->string('floor_name', 100)->default('Floor 1');
            $table->longText('layout_json');
            $table->unsignedInteger('booth_count')->default(0);
            $table->timestamps();

            $table->unique(['event_id', 'floor_number'], 'event_layouts_event_floor_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_layouts');
    }
};