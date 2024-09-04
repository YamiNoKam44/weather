<?php
declare(strict_types=1);

namespace App\Domain\Service;

class WeatherCalculator
{
    public function calculateWeather(array $averageWeather): float
    {
        return round(array_sum($averageWeather) / count($averageWeather), 1);
    }
}
