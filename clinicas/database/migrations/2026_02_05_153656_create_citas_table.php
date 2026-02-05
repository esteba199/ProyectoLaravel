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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            // Relación con usuarios
            $table->foreignId('usuario_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Datos de la cita
            $table->string('nombre_paciente', 100);
            $table->string('nombre_doctor', 100);
            $table->string('especialidad', 100);
            $table->date('fecha_cita');
            $table->time('hora_cita');

            // Información adicional
            $table->text('motivo')->nullable();
            $table->enum('estado', [
                'pendiente',
                'confirmada',
                'completada',
                'cancelada'
            ])->default('pendiente');

            $table->text('notas')->nullable();

            $table->timestamps();

            // Índices para mejor rendimiento
            $table->index('usuario_id');
            $table->index('fecha_cita');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
