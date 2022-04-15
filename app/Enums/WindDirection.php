<?php

namespace App\Enums;

enum WindDirection: string
{
    case N = 'северный';
    case NE = 'северо-восточный';
    case E = 'восточный';
    case SE = 'юго-восточный';
    case S = 'южный';
    case SW = 'юго-западный';
    case W = 'западный';
    case NW = 'северо-западный';
}
