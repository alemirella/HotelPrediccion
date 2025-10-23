<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Fecha de la predicción
            $table->date('date');

            // Campos predichos (nullable por seguridad)
            $table->integer('afluencia_turistica')->nullable();
            $table->integer('num_reservas')->nullable();
            $table->decimal('porcentaje_ocupacion', 5, 2)->nullable();
            $table->integer('clima')->nullable();
            $table->boolean('dia_festivo')->default(false);

            // Metadatos
            $table->string('model_version')->default('v1.0');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
