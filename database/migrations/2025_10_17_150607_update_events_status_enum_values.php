<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL/MariaDB - modify the enum to ensure all values are present
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft', 'finalized', 'published', 'ongoing', 'completed') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values if needed
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft', 'finalized', 'published') DEFAULT 'draft'");
    }
};
