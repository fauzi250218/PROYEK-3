<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('murids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id')->nullable(); // ✅ relasi ke kelas
            $table->string('nis')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('kata_sandi');
            $table->string('nomer_whatsapp')->nullable();
            $table->timestamps();

            // ✅ foreign key relasi
            $table->foreign('kelas_id')
                  ->references('id')
                  ->on('kelas')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('murids');
    }
};
