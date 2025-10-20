<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk menambahkan kolom kelas_id ke tabel nilai.
     */
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Tambahkan kolom kelas_id setelah guru_id
            if (!Schema::hasColumn('nilai', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('guru_id');

                // Tambahkan foreign key opsional
                $table->foreign('kelas_id')
                      ->references('id')
                      ->on('kelas')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Batalkan perubahan (rollback migration).
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Hapus foreign key dan kolom kelas_id jika ada
            if (Schema::hasColumn('nilai', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            }
        });
    }
};
