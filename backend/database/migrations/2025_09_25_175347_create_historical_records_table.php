<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historical_records', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario (hotel)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Datos históricos
            $table->date('date');
            $table->integer('clima')->nullable();                  // 1=Soleado, 2=Nublado, etc.
            $table->integer('afluencia_turistica')->nullable();    // número de visitantes
            $table->integer('num_reservas')->nullable();           // número de reservas
            $table->decimal('porcentaje_ocupacion', 5, 2)->nullable(); // 0–100 %
            $table->boolean('dia_festivo')->default(false);

            // Información adicional opcional
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historical_records');
    }
};
