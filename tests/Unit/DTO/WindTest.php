<?php

namespace Tests\Unit\DTO;

use App\DTO\Wind;
use Tests\TestCase;

class WindTest extends TestCase
{
    /**
     * @dataProvider getDataForTest
     */
    public function test_getting_wind_direction_name_by_degree(float $degree, string $name): void
    {
        $wind = new Wind(25, $degree);
        self::assertEquals($name, $wind->direction->name());
    }

    public function getDataForTest(): array
    {
        return [
            ['degree' => 0, 'name' => 'северный'],
            ['degree' => 22.4, 'name' => 'северный'],
            ['degree' => 22.5, 'name' => 'северо-восточный'],
            ['degree' => 45, 'name' => 'северо-восточный'],
            ['degree' => 67.4, 'name' => 'северо-восточный'],
            ['degree' => 67.5, 'name' => 'восточный'],
            ['degree' => 90, 'name' => 'восточный'],
            ['degree' => 112.4, 'name' => 'восточный'],
            ['degree' => 112.5, 'name' => 'юго-восточный'],
            ['degree' => 135, 'name' => 'юго-восточный'],
            ['degree' => 157.4, 'name' => 'юго-восточный'],
            ['degree' => 157.5, 'name' => 'южный'],
            ['degree' => 180, 'name' => 'южный'],
            ['degree' => 202.4, 'name' => 'южный'],
            ['degree' => 202.5, 'name' => 'юго-западный'],
            ['degree' => 225, 'name' => 'юго-западный'],
            ['degree' => 247.4, 'name' => 'юго-западный'],
            ['degree' => 247.5, 'name' => 'западный'],
            ['degree' => 270, 'name' => 'западный'],
            ['degree' => 292.4, 'name' => 'западный'],
            ['degree' => 292.5, 'name' => 'северо-западный'],
            ['degree' => 315, 'name' => 'северо-западный'],
            ['degree' => 337.4, 'name' => 'северо-западный'],
            ['degree' => 337.5, 'name' => 'северный'],
            ['degree' => 360, 'name' => 'северный'],
        ];
    }
}
