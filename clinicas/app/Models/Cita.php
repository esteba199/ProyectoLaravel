<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas'; // Forzamos el nombre de la tabla

    /**
     * Atributos asignables en masa (Sincronizados con español)
     */
    protected $fillable = [
        'usuario_id',
        'paciente',      // Antes: nombre_paciente
        'doctor',        // Antes: nombre_doctor
        'especialidad',
        'fecha',         // Antes: fecha_cita
        'hora',          // Antes: hora_cita
        'motivo',
        'estado',
        'notas',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'hora' => 'datetime:H:i',
        ];
    }

    /**
     * Relación inversa
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scopes corregidos a los nuevos nombres
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }


    public function scopeFuturas($query)
    {
        return $query->where('fecha', '>=', Carbon::today())
                     ->orderBy('fecha', 'asc')
                     ->orderBy('hora', 'asc');
    }


    public function esFutura(): bool
    {
        return $this->fecha >= Carbon::today();
    }


    public function claseEstado(): string
    {
        return match($this->estado) {
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'confirmada' => 'bg-blue-100 text-blue-800',
            'completada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }


    public function etiquetaEstado(): string
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            default => 'Desconocido',
        };
    }
}
