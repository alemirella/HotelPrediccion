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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario (hotel)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Datos de la predicción
            $table->date('date');                     // Fecha predicha
            $table->integer('predicted_count');       // Resultado del modelo
            $table->string('model_version')->default('v1.0'); // Versión del modelo ML
            $table->json('input_features')->nullable(); // Características usadas para predecir

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
