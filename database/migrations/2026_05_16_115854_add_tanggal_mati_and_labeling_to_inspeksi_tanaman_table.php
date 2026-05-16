<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inspeksi_tanaman', function (Blueprint $table) {
            $table->boolean('labeling')->after('catatan')->nullable();
            $table->date('tanggal_mati')->after('labeling')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_tanaman', function (Blueprint $table) {
            $table->dropColumn('labeling');
            $table->dropColumn('tanggal_mati');
    });
    }
};
