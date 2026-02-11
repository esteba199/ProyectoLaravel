<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.clave_api');
    }

    public function getCurrentWeather($lat, $lon)
    {
        if (!$lat || !$lon || !$this->apiKey) {
            return null;
        }

        $cacheKey = "weather_{$lat}_{$lon}";

        return Cache::remember($cacheKey, 3600, function () use ($lat, $lon) {
            $response = Http::get($this->baseUrl, [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'es'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    public function isAdverse($weatherData)
    {
        if (!$weatherData) return false;

        $main = $weatherData['weather'][0]['main'] ?? '';
        $adverseConditions = ['Rain', 'Snow', 'Thunderstorm', 'Drizzle', 'Tornado', 'Squall'];

        return in_array($main, $adverseConditions);
    }
}
