<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah sistem gaji menjadi berdasarkan kg laundry yang diselesaikan
     */
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan lagi
            $table->dropColumn(['base_salary', 'allowance', 'deduction']);
            
            // Tambah kolom untuk sistem gaji berbasis kg
            $table->decimal('total_kg_completed', 10, 2)->default(0)->after('position'); // Total kg yang diselesaikan
            $table->decimal('rate_per_kg', 10, 2)->default(500)->after('total_kg_completed'); // Tarif per kg (default Rp 500)
            $table->decimal('bonus', 12, 2)->default(0)->after('rate_per_kg'); // Bonus tambahan
            $table->text('notes')->nullable()->after('total_salary'); // Catatan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->decimal('base_salary', 12, 2)->after('position');
            $table->decimal('allowance', 12, 2)->default(0)->after('base_salary');
            $table->decimal('deduction', 12, 2)->default(0)->after('allowance');
            
            // Hapus kolom baru
            $table->dropColumn(['total_kg_completed', 'rate_per_kg', 'bonus', 'notes']);
        });
    }
};
