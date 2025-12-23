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
        // Modify the ENUM to include Indonesian categories
        DB::statement("ALTER TABLE expenses MODIFY COLUMN category ENUM('utilities', 'supplies', 'equipment', 'maintenance', 'other', 'Bahan Baku', 'Operasional', 'Gaji', 'Perawatan', 'Lain-lain') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM values
        DB::statement("ALTER TABLE expenses MODIFY COLUMN category ENUM('utilities', 'supplies', 'equipment', 'maintenance', 'other') NOT NULL");
    }
};
