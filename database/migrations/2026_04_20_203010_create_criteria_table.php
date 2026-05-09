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
        Schema::create('criterias', function(Blueprint $table) {
            $table->id();
            $table->string('nama_criteria');
            $table->string('satuan')->nullable();
            $table->decimal('bobot',5,2);
            $table->enum('tipe',['benefit','cost']);
            $table->enum('skala',['numerik','ordinal']);
            $table->enum('preference_function',['usual','quasi','linear','level','gaussian','v_shape'])->default('usual');
            $table->decimal('param_q',8,4)->nullable();
            $table->decimal('param_p',8,4)->nullable();
            $table->decimal('param_sigma',8,4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('criterias_ordinal', function(Blueprint $table){
            $table->id();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->string('label');
            $table->integer('nilai');
            $table->enum('operator',['eq','lt','lte','gt','gte','between'])->default('eq');
            $table->decimal('range_from',10,4)->nullable();
            $table->decimal('range_to',10,4)->nullable();
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias_ordinal');
        Schema::dropIfExists('criterias');
    }
};
