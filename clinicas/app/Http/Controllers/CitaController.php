<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CitaController extends Controller
{
    /**
     * Mostrar una lista del recurso.
     */
    public function index(Request $request): View
    {
        $query = Auth::user()->citas();

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_cita', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_cita', '<=', $request->fecha_hasta);
        }

        // Búsqueda por nombre de paciente o doctor
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre_paciente', 'like', "%{$buscar}%")
                  ->orWhere('nombre_doctor', 'like', "%{$buscar}%")
                  ->orWhere('especialidad', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por fecha más reciente
        $citas = $query->orderBy('fecha_cita', 'desc')
                             ->orderBy('hora_cita', 'desc')
                             ->paginate(10)
                             ->withQueryString(); // Mantiene los parámetros de búsqueda

        return view('citas.index', compact('citas'));
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create(): View
    {
        return view('citas.create');
    }

    /**
     * Almacenar un recurso recién creado en almacenamiento.
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'nombre_paciente' => ['required', 'string', 'max:100'],
            'nombre_doctor' => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha_cita' => ['required', 'date', 'after_or_equal:hoy'],
            'hora_cita' => ['required', 'date_format:H:i'],
            'razon' => ['nullable', 'string', 'max:500'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre_paciente.required' => 'El nombre del paciente es obligatorio',
            'nombre_doctor.required' => 'El nombre del doctor es obligatorio',
            'especialidad.required' => 'La especialidad es obligatoria',
            'fecha_cita.required' => 'La fecha de la cita es obligatoria',
            'fecha_cita.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora_cita.required' => 'La hora de la cita es obligatoria',
        ]);

        $cita = Auth::user()->citas()->create($validado);

        return redirect()->route('citas.index')
            ->with('exito', 'Cita creada exitosamente');
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show(Cita $cita): View
    {
        // Verificar que la cita pertenece al usuario autenticado
        $this->authorize('view', $cita);

        return view('citas.show', compact('cita'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit(Cita $cita): View
    {
        // Verificar que la cita pertenece al usuario autenticado
        $this->authorize('update', $cita);

        return view('citas.edit', compact('cita'));
    }

    /**
     * Actualizar el recurso especificado en almacenamiento.
     */
    public function update(Request $request, Cita $cita): RedirectResponse
    {
        // Verificar que la cita pertenece al usuario autenticado
        $this->authorize('update', $cita);

        $validado = $request->validate([
            'nombre_paciente' => ['required', 'string', 'max:100'],
            'nombre_doctor' => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha_cita' => ['required', 'date'],
            'hora_cita' => ['required', 'date_format:H:i'],
            'razon' => ['nullable', 'string', 'max:500'],
            'estado' => ['required', 'in:pendiente,confirmada,completada,cancelada'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ]);

        $cita->update($validado);

        return redirect()->route('citas.show', $cita)
            ->with('exito', 'Cita actualizada exitosamente');
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     */
    public function destroy(Cita $cita): RedirectResponse
    {
        // Verificar que la cita pertenece al usuario autenticado
        $this->authorize('delete', $cita);

        $cita->delete();

        return redirect()->route('citas.index')
            ->with('exito', 'Cita eliminada exitosamente');
    }

    /**
     * Actualiza solo el estado de la cita
     */
    public function updateEstado(Request $request, Cita $cita): RedirectResponse
    {
        $this->authorize('update', $cita);

        $validado = $request->validate([
            'estado' => ['required', 'in:pendiente,confirmada,completada,cancelada'],
        ]);

        $cita->update($validado);

        return back()->with('exito', 'Estado actualizado correctamente');
    }
}