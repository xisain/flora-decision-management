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
        Schema::create('tanaman_penerimaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->nullable()->constrained('penerimaans')->nullOnDelete();
            $table->string('scientific_name');
            $table->string('nomor_akses');
            $table->string('nama_lokal');
            $table->string('marga');
            $table->string('marga_jenis');
            $table->string('suku');
            $table->string('spesies');
            $table->string('author_name');
            $table->string('locality');
            $table->string('jumlah_material');
            $table->string('vak_no');
            $table->foreignId('collector_id')->nullable()->constrained('collector_infos')->nullOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaans');
        Schema::dropIfExists('tanaman_penerimaans');
    }
};
