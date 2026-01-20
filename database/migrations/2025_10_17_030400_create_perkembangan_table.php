<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (membuat tabel).
     */
    public function up(): void
    {
        Schema::create('perkembangan', function (Blueprint $table) {
            $table->id();

            // Relasi dengan guru (pastikan tabel 'guru' sudah ada)
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');

            // Relasi opsional dengan murid (bisa null)
            $table->foreignId('murid_id')->nullable()->constrained('murids')->onDelete('cascade');

            // Kolom tambahan
            $table->string('aspek')->nullable();
            $table->text('catatan')->nullable();

            // Timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Balik migration (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('perkembangan');
    }
};
