<?php

namespace App\Enums;

enum WindDirection
{
    case N;
    case NE;
    case E;
    case SE;
    case S;
    case SW;
    case W;
    case NW;

    public function name(): string
    {
        return match ($this) {
            self::N => __('direction.N'),
            self::NE => __('direction.NE'),
            self::E => __('direction.E'),
            self::SE => __('direction.SE'),
            self::S => __('direction.S'),
            self::SW => __('direction.SW'),
            self::W => __('direction.W'),
            self::NW => __('direction.NW'),
        };
    }
}
