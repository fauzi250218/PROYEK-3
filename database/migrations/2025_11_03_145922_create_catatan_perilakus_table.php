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
        Schema::create('catatan_perilakus', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel murid, guru, dan kelas
            $table->foreignId('murid_id')->constrained('murids')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // ✅ Tambahkan ini

            $table->date('tanggal')->default(now());
            $table->string('kategori')->nullable(); // Contoh: "Disiplin", "Kerjasama", "Tanggung Jawab"
            $table->text('deskripsi')->nullable();  // Catatan perilaku siswa
            $table->enum('status', ['positif', 'negatif', 'netral'])->default('netral');
            $table->timestamps();
        });
    }

    /**
     * Balikkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_perilakus');
    }
};
