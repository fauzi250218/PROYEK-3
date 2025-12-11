<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('modul', function (Blueprint $table) {
            $table->id();

            // relasi utama
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('sesi_id');
            $table->unsignedBigInteger('jadwal_id');

            // detail modul
            $table->string('judul');
            $table->string('file');
            $table->string('topik')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');

            $table->foreign('sesi_id')
                ->references('id')
                ->on('sesi')
                ->onDelete('cascade');

            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modul');
    }
};
