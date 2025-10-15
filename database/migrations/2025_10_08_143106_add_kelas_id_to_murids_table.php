<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('murids', function (Blueprint $table) {
            // ✅ Tambahkan hanya jika kolom belum ada
            if (!Schema::hasColumn('murids', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('id');

                $table->foreign('kelas_id')
                      ->references('id')
                      ->on('kelas')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('murids', function (Blueprint $table) {
            // ✅ Hapus hanya jika kolom ada
            if (Schema::hasColumn('murids', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            }
        });
    }
};
