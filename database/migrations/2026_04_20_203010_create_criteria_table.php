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
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('weight');
            $table->enum('type',['cost','benefit']);
            $table->enum('scale_type',['numeric','ordinal']);
            $table->string('preference_type');
            $table->float('p')->nullable();
            $table->float('q')->nullable();
            $table->timestamps();
        });
        Schema::create('ordinal_scales', function (Blueprint $table){
            $table->id();
            $table->foreignId('criteria_id')->constrained('criteria')->cascadeOnDelete();
            $table->string('label');
            $table->integer('value');
            $table->integer('position');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordinal_scales');
        Schema::dropIfExists('criteria');
    }
};
