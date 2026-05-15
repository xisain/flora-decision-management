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
        Schema::create('inspeksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_inspeksi');
            $table->text('catatan')->nullable();
            $table->enum('stage',['checkup','labeling','aklimatisasi','evaluasi','siap_tanam']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('inspeksi_tanaman', function (Blueprint $table){
            $table->id();
            $table->foreignId('inspeksi_id')->constrained('inspeksis');
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnUpdate();
            $table->enum('status',['hidup','mati','recovery','dormant']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('inspeksi_nilai_criteria', function(Blueprint $table){
            $table->id();
            $table->foreignId('inspeksi_tanaman_id')->constrained('inspeksi_tanaman')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->decimal('nilai_numeric',8,4)->nullable();
            $table->foreignId('criteria_ordinal_id')->nullable()->constrained('criterias_ordinal')->nullOnDelete();
            $table->unique(['inspeksi_tanaman_id','criteria_id']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeksi_nilai_criteria');
        Schema::dropIfExists('inspeksi_tanaman');
        Schema::dropIfExists('inspeksis');
    }
};
