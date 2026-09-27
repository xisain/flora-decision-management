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
        Schema::create('kategori_berita', function (Blueprint $table){
            $table->id();
            $table->string('nama_kategori');
            $table->string('deskripsi');
            $table->timestamps();
        });
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('slugs');
            $table->foreignId('kategori_berita_id')->constrained('kategori_berita');
            $table->string('judul');
            $table->text('content');
            $table->string('image_url');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status',['private','public','draft']);
            $table->integer('visitor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
        Schema::dropIfExists('kategori_berita');
    }
};
