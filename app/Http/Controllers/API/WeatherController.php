<?php

namespace App\Http\Controllers\API;

use App\Enums\Units;
use App\Exceptions\NotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWeatherRequest;
use App\Services\OpenWeatherMapApi;
use Illuminate\Http\JsonResponse;
use function response;

class WeatherController extends Controller
{
    public function __construct(
        private readonly OpenWeatherMapApi $openWeatherMapApi
    ) {}

    public function index(GetWeatherRequest $request): JsonResponse
    {
        try {
            $geoLocation = $request->getLocationParams() ?? $this->openWeatherMapApi->getGeoLocation($request['city']);
            $weather = $this->openWeatherMapApi
                ->getCurrentWeather($geoLocation, Units::tryFrom($request['units']) ?? Units::METRIC);
            return response()->json(data: $weather, options: JSON_UNESCAPED_UNICODE);
        } catch (NotFound) {
            return response()->json(['message' => 'not found'], 404);
        }
    }
}
