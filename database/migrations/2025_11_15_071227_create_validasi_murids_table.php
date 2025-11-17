<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasi_murids', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();   // NIS harus unik
            $table->unsignedBigInteger('kelas_id'); // relasi ke kelas
            $table->timestamps();

            // FK ke kelas
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_murids');
    }
};
