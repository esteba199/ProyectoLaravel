<?php

namespace App\Http\Controllers;

use App\Models\Cita; // <--- ASEGÚRATE DE QUE ESTO ESTÉ
use App\Models\User; // <--- Y ESTO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Y ESTO
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CitaController extends Controller 
{
    /**
     * Mostrar la lista de citas del usuario autenticado.
     */
    public function index(Request $request): View
    {
        // Esto fallará si no has puesto "public function citas()" en el modelo User
        $user = Auth::user();
        $query = $user->citas();

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fechas (columnas en español)
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // Búsqueda general
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('paciente', 'like', "%{$buscar}%")
                  ->orWhere('doctor', 'like', "%{$buscar}%")
                  ->orWhere('especialidad', 'like', "%{$buscar}%");
            });
        }

        // Ordenar y paginar
        $citas = $query->orderBy('fecha', 'desc')
                       ->orderBy('hora', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        return view('citas.index', compact('citas'));
    }

    public function create(): View
    {
        return view('citas.create');
    }

    /**
     * Guardar nueva cita.
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'paciente'     => ['required', 'string', 'max:100'],
            'doctor'       => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'hora'         => ['required', 'date_format:H:i'],
            'motivo'       => ['nullable', 'string', 'max:500'],
            'notas'        => ['nullable', 'string', 'max:1000'],
        ]);

        // Se crea a través de la relación para asignar automáticamente el usuario_id
        Auth::user()->citas()->create($validado);

        return redirect()->route('citas.index')->with('exito', '¡Cita creada con éxito!');
    }

    public function show(Cita $cita): View
    {
        // Verificación de propiedad manual (más segura tras cambios de nombres)
        if ($cita->usuario_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita): View
    {
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }

        return view('citas.edit', compact('cita'));
    }

    /**
     * Actualizar cita existente.
     */
    public function update(Request $request, Cita $cita): RedirectResponse
    {
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }

        $validado = $request->validate([
            'paciente'     => ['required', 'string', 'max:100'],
            'doctor'       => ['required', 'string', 'max:100'],
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha'        => ['required', 'date'],
            'hora'         => ['required', 'date_format:H:i'],
            'motivo'       => ['nullable', 'string', 'max:500'],
            'estado'       => ['required', 'in:pendiente,confirmada,completada,cancelada'],
            'notas'        => ['nullable', 'string', 'max:1000'],
        ]);

        $cita->update($validado);

        return redirect()->route('citas.show', $cita)->with('exito', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }

        $cita->delete();

        return redirect()->route('citas.index')->with('exito', 'Cita eliminada.');
    }

    /**
     * Cambiar solo el estado (para acciones rápidas).
     */
    public function updateEstado(Request $request, Cita $cita): RedirectResponse
    {
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }

        $validado = $request->validate([
            'estado' => ['required', 'in:pendiente,confirmada,completada,cancelada'],
        ]);

        $cita->update($validado);

        return back()->with('exito', 'El estado ha sido actualizado.');
    }
}