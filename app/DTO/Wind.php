<?php

namespace App\DTO;

use App\Enums\WindDirection;
use JetBrains\PhpStorm\ArrayShape;

class Wind implements \JsonSerializable
{
    public readonly ?WindDirection $direction;

    public function __construct(public readonly float $speed, float $directionDegree)
    {
        $this->direction = $this->speed
            ? $this->getDirectionName($directionDegree)
            : null;
    }

    private function getDirectionName(float $directionDegree): WindDirection
    {
        return WindDirection::cases()[round((($directionDegree / 45) + 0.5 ) % 8)];
    }

    #[ArrayShape(['direction' => "\App\Enums\WindDirection|null", 'speed' => "int"])]
    public function jsonSerialize(): array
    {
        return ['direction' => $this->direction->value, 'speed' => (int) round($this->speed)];
    }
}
