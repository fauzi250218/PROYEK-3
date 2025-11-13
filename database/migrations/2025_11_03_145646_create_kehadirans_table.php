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
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->constrained('murids')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Balikkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
