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
        Schema::create('penerimaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_explorasi');
            $table->string('jenis_form');
            $table->date('tanggal_penerimaan');
            $table->string('tempat_asal');
            $table->string('country');
            $table->string('source');
            $table->string('native');
            $table->foreignId('exploration_team_id')->nullable()->constrained('exploration_team');
            $table->timestamps();
        });
        Schema::create('tanaman_infos', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name');
            $table->string('nama_lokal')->nullable();
            $table->string('marga')->nullable();
            $table->string('marga_jenis')->nullable();
            $table->string('suku')->nullable();
            $table->string('spesies')->nullable();
            $table->string('author_name')->nullable();

            $table->timestamps();
        });
        Schema::create('tanaman_penerimaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->nullable()->constrained('penerimaans')->nullOnDelete();
            $table->string('jumlah_material');
            $table->string('nomor_akses')->unique();
            $table->foreignId('tanaman_info_id')->constrained('tanaman_infos')->cascadeOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('collector_infos')->nullOnDelete();
            $table->string('locality');
            $table->string('vak_no');
            $table->timestamps();
        });
        Schema::create('tanaman', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tanaman_penerimaan_id')->constrained('tanaman_penerimaans');
            $table->integer('nomor_urut');
            $table->timestamps();
        });
        // Schema::create('tanaman_status_logs', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
        //     $table->enum('stage', ['penyemaian', 'checkup', 'aklimatisasi', 'siap_tanam']);
        //     $table->enum('status', ['hidup', 'mati', 'recovery', 'dormant']);
        //     $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        //     $table->text('catatan')->nullable();
        //     $table->timestamp('tanggal_proses');
        //     $table->timestamps();
        // });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('tanaman_status_logs');
        Schema::dropIfExists('tanaman');
        Schema::dropIfExists('tanaman_penerimaans');
        Schema::dropIfExists('tanaman_infos');
        Schema::dropIfExists('penerimaans');
    }
};
