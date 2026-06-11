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
        Schema::create('kebun_raya_koleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
            $table->foreignId('inspeksi_tanaman_id')->constrained('inspeksi_tanaman')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('ranking')->default(0);
            $table->decimal('net_flow', 8, 4)->default(0);
            $table->decimal('leaving_flow', 8, 4)->default(0);
            $table->decimal('entering_flow', 8, 4)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebun_raya_koleksi');
    }
};
