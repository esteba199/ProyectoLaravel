<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'usuario_id',
        'nombre_paciente',
        'nombre_doctor',
        'especialidad',
        'fecha_cita',
        'hora_cita',
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
            'fecha_cita' => 'date',
            'hora_cita' => 'datetime:H:i',
        ];
    }

    /**
     * Relación inversa: una cita pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope: filtrar citas por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope: citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha_cita', '>=', Carbon::today())
                     ->orderBy('fecha_cita', 'asc')
                     ->orderBy('hora_cita', 'asc');
    }

    /**
     * Verifica si la cita es futura
     */
    public function esFutura(): bool
    {
        return $this->fecha_cita >= Carbon::today();
    }

    /**
     * Obtiene la clase CSS para el badge según el estado
     */
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

    /**
     * Obtiene el texto del estado en español
     */
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
