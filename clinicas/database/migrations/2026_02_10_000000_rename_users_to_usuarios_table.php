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
        // 1. Renombrar tabla users a usuarios
        if (Schema::hasTable('users')) {
            Schema::rename('users', 'usuarios');
        }

        // 2. Renombrar campos en usuarios (antes users)
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'name')) {
                $table->renameColumn('name', 'nombre');
            }
            if (Schema::hasColumn('usuarios', 'email')) {
                $table->renameColumn('email', 'correo');
            }
            if (Schema::hasColumn('usuarios', 'password')) {
                $table->renameColumn('password', 'clave');
            }
        });

        // 3. Renombrar campos en tabla citas
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (Schema::hasColumn('citas', 'nombre_paciente')) {
                    $table->renameColumn('nombre_paciente', 'paciente');
                }
                if (Schema::hasColumn('citas', 'nombre_doctor')) {
                    $table->renameColumn('nombre_doctor', 'doctor');
                }
                if (Schema::hasColumn('citas', 'fecha_cita')) {
                    $table->renameColumn('fecha_cita', 'fecha');
                }
                if (Schema::hasColumn('citas', 'hora_cita')) {
                    $table->renameColumn('hora_cita', 'hora');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en citas
        if (Schema::hasTable('citas')) {
            Schema::table('citas', function (Blueprint $table) {
                if (Schema::hasColumn('citas', 'paciente')) {
                    $table->renameColumn('paciente', 'nombre_paciente');
                }
                if (Schema::hasColumn('citas', 'doctor')) {
                    $table->renameColumn('doctor', 'nombre_doctor');
                }
                if (Schema::hasColumn('citas', 'fecha')) {
                    $table->renameColumn('fecha', 'fecha_cita');
                }
                if (Schema::hasColumn('citas', 'hora')) {
                    $table->renameColumn('hora', 'hora_cita');
                }
            });
        }

        // Revertir campos en usuarios
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (Schema::hasColumn('usuarios', 'nombre')) {
                    $table->renameColumn('nombre', 'name');
                }
                if (Schema::hasColumn('usuarios', 'correo')) {
                    $table->renameColumn('correo', 'email');
                }
                if (Schema::hasColumn('usuarios', 'clave')) {
                    $table->renameColumn('clave', 'password');
                }
            });
        }

        // Revertir nombre de tabla
        if (Schema::hasTable('usuarios')) {
            Schema::rename('usuarios', 'users');
        }
    }
};