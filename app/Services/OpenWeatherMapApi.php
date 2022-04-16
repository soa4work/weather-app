<?php

namespace App\Services;

use App\DTO\GeoLocation;
use App\DTO\WeatherData;
use App\DTO\Wind;
use App\Enums\Units;
use App\Exceptions\NotFound;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenWeatherMapApi
{
    /**
     * @throws NotFound
     */
    public function getGeoLocation(string $city): GeoLocation
    {
        $response = $this->getResponse('/geo/1.0/direct', ['q' => $city, 'limit' => 1])[0] ?? [];
        return new GeoLocation($response['lon'] ?? null, $response['lat'] ?? null);
    }

    /**
     * @throws NotFound
     */
    public function getCurrentWeather(GeoLocation $location, Units $units): WeatherData
    {
        $response = $this->getResponse(
            '/data/2.5/weather',
            [
                'lat' => $location->latitude,
                'lon' => $location->longitude,
                'lang' => config('app.locale'),
                'units' => $units->value,
            ]
        );
        return new WeatherData(
            description: $response['weather'][0]['description'] ?? '',
            temp: $response['main']['temp'] ?? 0,
            pressure: $response['main']['pressure'] ?? 0,
            humidity: $response['main']['humidity'] ?? 0,
            wind: new Wind(
                speed: $response['wind']['speed'] ?? 0,
                directionDegree: $response['wind']['deg'] ?? 0
            )
        );
    }

    /**
     * @throws NotFound
     */
    private function getResponse(string $url, array $params)
    {
        $key = md5($url . implode('_', $params));
        $response = Cache::remember($key, config('services.owm.cache_ttl'), static function () use ($params, $url) {
            Log::info('getting data from 3party API', [$url, $params]);
            try {
                return Http::get(
                    config('services.owm.url') . $url,
                    array_merge(['appid' => config('services.owm.key')], $params)
                )->throw()->json();
            } catch (\Throwable $e) {
                Log::info($e->getMessage(), [$url, $params]);
                return [];
            }
        });
        return $response ?: throw new NotFound();
    }
}
