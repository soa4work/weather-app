<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    /**
     * @dataProvider getDatSet
     */
    public function test_app_returns_responses(array $request, array $responses, array $expected, int $statusCode): void
    {
        $this->mockHttpClient($responses);
        $response = $this->get('/api/current?' . http_build_query($request));
        $response->assertStatus($statusCode);
        $response->assertJson($expected);
    }

    private function mockHttpClient(array $responses): void
    {
        $stub = Http::fakeSequence();
        foreach ($responses as $response) {
            $stub->push($response);
        }
    }

    public function getDatSet(): \Generator
    {
        $successResponse = [
            'description' => 'ясно',
            'temp' => 16,
            'pressure' => 1023,
            'humidity' => 65,
            'wind' => ['direction' => 'северный', 'speed' => 2],
        ];
        $weatherData = [
            'weather' => [['description' => 'ясно']],
            'main' => ['temp' => 16.3, 'pressure' => 1023, 'humidity' => 65],
            'wind' => ['speed' => 1.8, 'deg' => 350],
        ];
        yield [
            'request' => ['city' => 'tomsk'],
            'stubs' => [
                [['lon' => 1.1, 'lat' => 1.1]],
                $weatherData,
            ],
            'expected' => $successResponse,
            'statusCode' => 200,
        ];
        yield [
            'request' => ['latitude' => 1.1, 'longitude' => 1.1],
            'stubs' => [
                $weatherData,
            ],
            'expected' => $successResponse,
            'statusCode' => 200,
        ];
        yield [
            'request' => ['city' => 'unknown'],
            'stubs' => [],
            'expected' => ['message' => 'Not found.'],
            'statusCode' => 404,
        ];
        yield [
            'request' => ['city' => 'tomsk', 'units' => 'some'],
            'stubs' => [],
            'expected' => ['message' => 'Выбранное значение для units некорректно.'],
            'statusCode' => 422,
        ];
    }
}
