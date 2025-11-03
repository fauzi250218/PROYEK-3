<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambahkan kolom group_id ke tabel jadwals
     * untuk menandai jadwal yang berulang (1 semester)
     */
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwals', 'group_id')) {
                $table->string('group_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Hapus kolom group_id jika di-rollback
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('jadwals', 'group_id')) {
                $table->dropColumn('group_id');
            }
        });
    }
};
