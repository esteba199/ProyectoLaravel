<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // ✅ CORRECTO: App con mayúscula
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;

class PerfilesController extends Controller
{
    
    public function editar(): View
    {
        $usuario = Auth::user();

        // Verificar que sea un modelo válido antes de llamar métodos
        $datosClima = null;
        if ($usuario instanceof User && $usuario->tieneUbicacion()) {
            $datosClima = $this->obtenerDatosClima($usuario->latitud, $usuario->longitud);
        }

        return view('perfil.editar', [
            'usuario' => $usuario,
            'datosClima' => $datosClima,
        ]);
    }


    public function actualizar(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'nombre' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            ]);

            $usuario = Auth::user();
            if (!$usuario instanceof User) {
                return redirect()->back()->with('error', 'Usuario no válido.');
            }

            $usuario->name = trim($request->nombre);
            $usuario->email = trim($request->email);

            try {
                $usuario->save();
            } catch (QueryException $e) {
                Log::error('Error al guardar perfil en DB: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error al guardar perfil');
            }

            Log::info('Perfil actualizado para usuario ID: ' . $usuario->id);
            return redirect()->route('perfil.editar')->with('exito', 'Perfil actualizado correctamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar perfil')->withInput();
        }
    }

    public function actualizarUbicacion(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'latitud' => ['required', 'numeric', 'between:-90,90'],
                'longitud' => ['required', 'numeric', 'between:-180,180'],
            ]);

            $usuario = Auth::user();
            if (!$usuario instanceof User) {
                return redirect()->back()->with('error', 'Usuario no válido.');
            }

            $usuario->latitud = round((float)$request->latitud, 6);
            $usuario->longitud = round((float)$request->longitud, 6);

            $direccion = $this->geocodificacionInversa($usuario->latitud, $usuario->longitud);

            if ($direccion) {
                $usuario->direccion = $direccion['direccion_formateada'] ?? null;
                $usuario->ciudad = $direccion['ciudad'] ?? null;
                $usuario->pais = $direccion['pais'] ?? null;
            }

            try {
                $usuario->save();
            } catch (QueryException $e) {
                Log::error('Error al guardar ubicación en DB: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error al guardar ubicación');
            }

            Log::info('Ubicación actualizada para usuario ID: ' . $usuario->id);
            return redirect()->route('perfil.editar')->with('exito', 'Ubicación actualizada correctamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error al actualizar ubicación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar ubicación')->withInput();
        }
    }

    private function geocodificacionInversa(float $latitud, float $longitud): ?array
    {
        $claveApi = config('services.google_maps.api_key') ?? config('servicios.google_maps.clave_api');

        if (!$claveApi) {
            Log::warning('Clave API de Google Maps no configurada');
            return null;
        }

        try {
            $respuesta = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$latitud},{$longitud}",
                'key' => $claveApi,
                'language' => 'es',
            ]);

            if ($respuesta->successful() && $respuesta->json('status') === 'OK') {
                $resultado = $respuesta->json('results')[0] ?? null;
                if ($resultado) {
                    return [
                        'direccion_formateada' => $resultado['formatted_address'] ?? null,
                        'ciudad' => $this->extraerComponenteDireccion($resultado, 'locality'),
                        'pais' => $this->extraerComponenteDireccion($resultado, 'country'),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error en geocodificación inversa: ' . $e->getMessage());
        }

        return null;
    }


    private function extraerComponenteDireccion(array $resultado, string $tipo): ?string
    {
        foreach ($resultado['address_components'] ?? [] as $componente) {
            if (in_array($tipo, $componente['types'])) {
                return $componente['long_name'];
            }
        }
        return null;
    }


    private function obtenerDatosClima(float $latitud, float $longitud): ?array
    {
        $claveApi = config('services.openweather.api_key') ?? config('servicios.openweather.clave_api');

        if (!$claveApi) {
            Log::warning('Clave API de OpenWeather no configurada');
            return null;
        }

        try {
            $respuesta = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $latitud,
                'lon' => $longitud,
                'appid' => $claveApi,
                'units' => 'metric',
                'lang' => 'es',
            ]);

            if ($respuesta->successful()) {
                $datos = $respuesta->json();
                $clima = $datos['weather'][0] ?? [];

                return [
                    'temperatura' => round($datos['main']['temp'] ?? 0),
                    'sensacion_termica' => round($datos['main']['feels_like'] ?? 0),
                    'descripcion' => ucfirst($clima['description'] ?? ''),
                    'icono' => $clima['icon'] ?? '',
                    'humedad' => $datos['main']['humidity'] ?? null,
                    'velocidad_viento' => $datos['wind']['speed'] ?? null,
                    'clima_principal' => $clima['main'] ?? '',
                    'es_adverso' => $this->climaAdverso($clima['main'] ?? ''),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error al obtener datos del clima: ' . $e->getMessage());
        }

        return null;
    }


    private function climaAdverso(string $climaPrincipal): bool
    {
        $condicionesAdversas = ['Rain', 'Snow', 'Thunderstorm', 'Drizzle', 'Squall', 'Tornado'];
        return in_array($climaPrincipal, $condicionesAdversas);
    }
}
