<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            // Pengguna pertama
            $table->unsignedBigInteger('user_one_id');
            $table->enum('user_one_role', ['murid', 'guru']);

            // Pengguna kedua
            $table->unsignedBigInteger('user_two_id');
            $table->enum('user_two_role', ['murid', 'guru']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
