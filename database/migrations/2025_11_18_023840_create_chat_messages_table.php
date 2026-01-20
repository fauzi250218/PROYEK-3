<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('chat_room_id');

            // Siapa pengirim (bisa murid atau guru)
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_role', ['murid', 'guru']);

            $table->text('message');
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
