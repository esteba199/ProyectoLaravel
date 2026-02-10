<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importante añadir esta línea

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // INDICAMOS LA TABLA EN ESPAÑOL
    protected $table = 'usuarios';

    // INDICAMOS LAS COLUMNAS EN ESPAÑOL
    protected $fillable = [
        'nombre',
        'correo',
        'clave',
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * ESTA ES LA FUNCIÓN QUE TE FALTA
     * Es la que permite usar $user->citas()
     */
    public function citas(): HasMany
    {
        // Conecta este usuario con la tabla 'citas' usando la columna 'usuario_id'
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    /**
     * IMPORTANTE: Le decimos a Laravel que la contraseña se llama 'clave'
     */
    public function getAuthPassword()
    {
        return $this->clave;
    }
}