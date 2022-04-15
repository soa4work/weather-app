<?php

namespace App\DTO;

use JetBrains\PhpStorm\ArrayShape;
use App\DTO\Wind;

class WeatherData implements \JsonSerializable
{
    public function __construct(
        public readonly string $description,
        public readonly float $temp,
        public readonly float $pressure,
        public readonly float $humidity,
        public readonly Wind $wind,
    ) {}

    #[ArrayShape(['description' => "string", 'temp' => "int", 'pressure' => "int", 'humidity' => "int", 'wind' => Wind::class])]
    public function jsonSerialize(): array
    {
        return [
            'description' => $this->description,
            'temp' => (int) round($this->temp),
            'pressure' => (int) round($this->pressure),
            'humidity' => (int) round($this->humidity),
            'wind' => $this->wind,
        ];
    }
}
