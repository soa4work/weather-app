<?php

namespace App\DTO;

class GeoLocation
{
    public function __construct(public readonly ?float $longitude, public readonly ?float $latitude)
    {
    }
}
