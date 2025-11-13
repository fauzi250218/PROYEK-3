<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // relasi ke tabel users
            $table->string('kelas')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('nomer_whatsapp')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamps();

            // Hubungkan user_id ke tabel users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('guru');
    }
};
