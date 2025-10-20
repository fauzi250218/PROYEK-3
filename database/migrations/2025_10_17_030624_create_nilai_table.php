<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();

            // ✅ Relasi ke murid (pastikan tabel 'murids' benar, bukan 'murid')
            $table->foreignId('murid_id')
                  ->constrained('murids')
                  ->onDelete('cascade');

            // ✅ Relasi ke guru (pastikan tabelnya 'guru', bukan 'gurus')
            $table->foreignId('guru_id')
                  ->constrained('guru')
                  ->onDelete('cascade');

            // ✅ Relasi ke kelas
            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');

            // ✅ Kolom data nilai
            $table->string('mata_pelajaran', 100);
            $table->integer('tugas')->nullable();
            $table->integer('ulangan_harian')->nullable();
            $table->integer('uts')->nullable();
            $table->integer('uas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        // Tidak perlu disable constraint, cukup dropIfExists
        Schema::dropIfExists('nilai');
    }
};