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
        Schema::create('penyemaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_semai');
            $table->string('lokasi_semai')->nullable();
            $table->mediumText('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('penyemaian_tanaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyemaian_id')->nullable()->constrained('penyemaian')->cascadeOnDelete();
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
            $table->enum('status', ['hidup', 'mati', 'recovery', 'dormant'])->default('hidup');
            $table->mediumText('catatan')->nullable();
            $table->unique(['penyemaian_id','tanaman_id']);
            $table->timestamps();
        });
        Schema::create('tanaman_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
            $table->enum('stage',['penyemaian','checkup','labeling','aklimatisasi','evaluasi','siap_tanam']);
            $table->enum('status', ['hidup', 'mati', 'recovery', 'dormant']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanaman_status_logs');
        Schema::dropIfExists('penyemaian_tanaman');
        Schema::dropIfExists('penyemaian');
    }
};
