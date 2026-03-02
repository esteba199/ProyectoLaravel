<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Database\Eloquent\Relations\HasMany;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios'; 

    protected $fillable = ['nombre', 'correo', 'clave', 'latitud', 'longitud', 'es_admin'];

    protected $hidden = ['clave', 'remember_token'];

    // Esto le dice a Laravel que use 'clave' para el login
    public function getAuthPassword()
    {
        return $this->clave;
    }

    // Esto soluciona el error $user->citas()
    public function citas()//: HasMany
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }
}