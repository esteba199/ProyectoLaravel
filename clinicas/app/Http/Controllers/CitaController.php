<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User; // Ahora se usará explícitamente abajo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CitaController extends Controller 
{
    public function index(Request $request, \App\Services\WeatherService $weatherService): View
    {
        /** @var User $user */
        $user = Auth::user(); 
        
        $clima = $weatherService->getCurrentWeather($user->latitud, $user->longitud);
        $climaAdverso = $weatherService->isAdverse($clima);

        // Si aquí da error, es que NO has puesto la función en el modelo User.php
        $query = $user->citas();

        // ... resto de los filtros (están bien en tu código) ...

        $citas = $query->orderBy('fecha', 'desc')
                       ->orderBy('hora', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        return view('citas.index', compact('citas', 'clima', 'climaAdverso'));
    }

    public function create(): View
    {
        return view('citas.create');
    }

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

        /** @var User $user */
        $user = Auth::user();

        // Esta línea fallará si no existe la relación en el modelo User
        $user->citas()->create($validado);

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