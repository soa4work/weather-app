<?php

namespace App\Service;

use App\DTO\GeoLocation;
use App\DTO\WeatherData;
use App\DTO\Wind;
use App\Enums\Unit;
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
        $response = $this->getResponse('/geo/1.0/direct', ['q' => $city, 'limit' => 1])[0];
        return new GeoLocation($response['lon'] ?? null, $response['lat'] ?? null);
    }

    /**
     * @throws NotFound
     */
    public function getCurrentWeather(GeoLocation $location, Unit $units = Unit::METRIC): WeatherData
    {
        $response = $this->getResponse(
            '/data/2.5/weather',
            [
                'lat' => $location->latitude,
                'lon' => $location->longitude,
                'lang' => env('LOCAL'),
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
        $response = Cache::remember($key, env('CACHE_TTL'), static function () use ($params, $url) {
            Log::info('getting location data from 3party API');
            return Http::get(
                env('OPENWEATHERMAP_API_URL') . $url,
                array_merge(['appid' => env('OPENWEATHERMAP_API_KEY')], $params)
            )->json();
        });
        if (empty($response)) {
            throw new NotFound();
        }
        return $response;
    }
}
