<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    
    use HasFactory, Notifiable;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos al serializar
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: un usuario tiene muchas citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    /**
     * Relación: un usuario tiene una ubicación
     */
    public function ubicacion()
    {
        return $this->hasOne(Ubicacion::class, 'usuario_id');
    }
}
