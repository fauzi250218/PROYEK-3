<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();

            // relasi utama
            $table->unsignedBigInteger('sesi_id');
            $table->unsignedBigInteger('murid_id');
            $table->unsignedBigInteger('jadwal_id');

            // status hadir
            $table->enum('status', ['H', 'I', 'A', 'S'])->default('A');

            $table->timestamps();

            // foreign key
            $table->foreign('sesi_id')
                ->references('id')
                ->on('sesi')
                ->onDelete('cascade');

            $table->foreign('murid_id')
                ->references('id')
                ->on('murids')
                ->onDelete('cascade');

            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');

            // 1 murid hanya boleh satu absensi per sesi
            $table->unique(['sesi_id', 'murid_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kehadiran');
    }
};
