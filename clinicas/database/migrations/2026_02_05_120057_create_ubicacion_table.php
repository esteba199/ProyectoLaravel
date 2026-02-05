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
        Schema::create('ubicacion', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitud', 10, 8)->nullable();  // Latitud
            $table->decimal('longitud', 11, 8)->nullable(); // Longitud
            $table->string('direccion', 500)->nullable();   // Dirección completa
            $table->string('ciudad', 100)->nullable();      // Ciudad
            $table->string('pais', 100)->nullable();        // País
            $table->timestamps();                            // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicacion');
    }
};
