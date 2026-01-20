<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tagihan_spp', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('murid_id');

            $table->string('bulan');
            $table->integer('tahun');
            $table->integer('nominal');

            // STATUS TAGIHAN
            $table->enum('status', ['belum_bayar', 'lunas'])
                ->default('belum_bayar');

            $table->timestamps();

            $table->foreign('murid_id')
                ->references('id')
                ->on('murids')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_spp');
    }
};
