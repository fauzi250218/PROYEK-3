<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tagihan_id');

            // MIDTRANS
            $table->string('order_id')->unique();
            $table->integer('gross_amount');
            $table->string('snap_token')->nullable();

            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();

            $table->timestamps();

            $table->foreign('tagihan_id')
                ->references('id')
                ->on('tagihan_spp')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
