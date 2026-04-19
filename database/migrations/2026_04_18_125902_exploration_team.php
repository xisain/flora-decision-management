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
        Schema::create('exploration_team', function(Blueprint $table){
            $table->id();
            $table->string('nama_tim');
            $table->string('deskripsi_team');
            $table->string('lokasi_explorasi');
            $table->timestamps();
        });
        Schema::create('exploration_team_member', function(Blueprint $table) {
            $table->id();
            $table->foreignId('exploration_team_id')->nullable()->constrained('exploration_team')->nullOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('collector_infos')->nullOnDelete();
            $table->string('Peran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('exploration_team_member');
        Schema::drop('exploration_team');

    }
};
