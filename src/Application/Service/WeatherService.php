<?php
declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Repository\WeatherRepositoryInterface;
use App\Domain\Service\WeatherCalculator;
use App\UI\Web\Request\AverageTemperatureRequest;
use DateTimeImmutable;

readonly class WeatherService
{
    public function __construct(
        private WeatherRepositoryInterface $weatherRepository,
        private WeatherCalculator          $weatherCalculator
    )
    {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function calculateWeatherTemperatureByCitiesAndDate(AverageTemperatureRequest $request): float
    {
        $averageTemperatures = $this->weatherRepository->getWeatherByCitiesAndDate(
            $request->getCities(), new DateTimeImmutable($request->getDate())
        );

        return $this->weatherCalculator->calculateWeather($averageTemperatures);
    }
}
