<?php

namespace App\Http\Controllers\API;

use App\Enums\Unit;
use App\Exceptions\NotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWeatherRequest;
use App\Service\OpenWeatherMapApi;
use Illuminate\Http\JsonResponse;
use function abort;
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
            $weather = $this->openWeatherMapApi->getCurrentWeather($geoLocation, Unit::from($request['units']));
            return response()->json([$weather]);
        } catch (NotFound) {
            return abort(404, 'not found');
        }
    }
}
