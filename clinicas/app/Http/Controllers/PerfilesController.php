<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PerfilesController extends Controller
{
    /**
     * Muestra el formulario de edición del perfil
     */
    public function editar(): View
    {
        $usuario = Auth::user();
        
        // Obtener datos del clima si el usuario tiene ubicación
        $datosClima = null;
        if ($usuario->ubicacion) {
            $coords = $usuario->ubicacion;
            $datosClima = $this->obtenerDatosClima($coords->latitud, $coords->longitud);
        }
        
        return view('profile.edit', [
            'usuario' => $usuario,
            'datosClima' => $datosClima,
        ]);
    }

    /**
     * Actualiza los datos del perfil
     */
    public function actualizar(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $usuario = Auth::user();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Actualiza la ubicación del usuario
     */
    public function actualizarUbicacion(Request $request): RedirectResponse
    {
        $request->validate([
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $usuario = Auth::user();

        // Guardar coordenadas
        $ubicacion = $usuario->ubicacion ?? $usuario->ubicacion()->create([]);
        $ubicacion->latitud = $request->latitud;
        $ubicacion->longitud = $request->longitud;

        // Obtener dirección mediante geocodificación inversa
        $direccion = $this->geocodificacionInversa($request->latitud, $request->longitud);
        
        if ($direccion) {
            $ubicacion->direccion = $direccion['formatted_address'] ?? null;
            $ubicacion->ciudad = $direccion['city'] ?? null;
            $ubicacion->pais = $direccion['country'] ?? null;
        }

        $ubicacion->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Ubicación actualizada correctamente');
    }

    /**
     * Geocodificación inversa usando Google Maps API
     */
    private function geocodificacionInversa(float $latitud, float $longitud): ?array
    {
        $apiKey = config('services.google_maps.api_key');
        if (!$apiKey) return null;

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$latitud},{$longitud}",
                'key' => $apiKey,
                'language' => 'es',
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $resultados = $response->json('results');
                
                if (!empty($resultados)) {
                    $resultado = $resultados[0];
                    return [
                        'formatted_address' => $resultado['formatted_address'] ?? null,
                        'city' => $this->extraerComponenteDireccion($resultado, 'locality'),
                        'country' => $this->extraerComponenteDireccion($resultado, 'country'),
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error en geocodificación inversa: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Extrae un componente específico de la dirección
     */
    private function extraerComponenteDireccion(array $resultado, string $tipo): ?string
    {
        foreach ($resultado['address_components'] ?? [] as $componente) {
            if (in_array($tipo, $componente['types'])) {
                return $componente['long_name'];
            }
        }
        return null;
    }

    /**
     * Obtiene datos meteorológicos de OpenWeather API
     */
    private function obtenerDatosClima(float $latitud, float $longitud): ?array
    {
        $apiKey = config('services.openweather.api_key');
        if (!$apiKey) return null;

        try {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $latitud,
                'lon' => $longitud,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'es',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temperatura' => round($data['main']['temp']),
                    'sensacion' => round($data['main']['feels_like']),
                    'descripcion' => ucfirst($data['weather'][0]['description'] ?? ''),
                    'icono' => $data['weather'][0]['icon'] ?? '',
                    'humedad' => $data['main']['humidity'],
                    'velocidad_viento' => $data['wind']['speed'],
                    'clima_principal' => $data['weather'][0]['main'] ?? '',
                    'es_adverso' => $this->climaAdverso($data['weather'][0]['main'] ?? ''),
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Error al obtener datos del clima: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Determina si el clima es adverso
     */
    private function climaAdverso(string $climaPrincipal): bool
    {
        $condicionesAdversas = ['Rain', 'Snow', 'Thunderstorm', 'Drizzle', 'Squall', 'Tornado'];
        return in_array($climaPrincipal, $condicionesAdversas);
    }
}
