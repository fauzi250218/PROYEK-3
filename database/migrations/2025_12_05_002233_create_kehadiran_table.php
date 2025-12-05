<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sesi_id');
            $table->unsignedBigInteger('murid_id');
            $table->enum('status', ['H', 'I', 'A', 'S'])->default('A'); 
            $table->timestamps();

            $table->foreign('sesi_id')->references('id')->on('sesi')->onDelete('cascade');
            $table->foreign('murid_id')->references('id')->on('murids')->onDelete('cascade');

            $table->unique(['sesi_id', 'murid_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
