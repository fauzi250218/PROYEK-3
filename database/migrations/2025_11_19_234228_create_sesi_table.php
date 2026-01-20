<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sesi', function (Blueprint $table) {
            $table->id();

            // relasi utama
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('jadwal_id');

            // detail sesi
            $table->string('judul_sesi');
            $table->string('topik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();

            // foreign key
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');

            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesi');
    }
};
