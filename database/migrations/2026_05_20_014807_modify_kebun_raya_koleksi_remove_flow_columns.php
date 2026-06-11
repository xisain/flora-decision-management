<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kebun_raya_koleksi', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu sebelum drop kolom
            $table->dropForeign(['inspeksi_tanaman_id']);
            $table->dropColumn(['inspeksi_tanaman_id', 'ranking', 'net_flow', 'leaving_flow', 'entering_flow']);
        });
    }

    public function down(): void
    {
        Schema::table('kebun_raya_koleksi', function (Blueprint $table) {
            $table->foreignId('inspeksi_tanaman_id')->constrained('inspeksi_tanaman')->cascadeOnDelete();
            $table->integer('ranking')->default(0);
            $table->decimal('net_flow', 8, 4)->default(0);
            $table->decimal('leaving_flow', 8, 4)->default(0);
            $table->decimal('entering_flow', 8, 4)->default(0);
        });
    }
};
