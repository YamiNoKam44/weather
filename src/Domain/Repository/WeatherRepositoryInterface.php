<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use DateTimeImmutable;

interface WeatherRepositoryInterface
{
    public function getWeatherByCitiesAndDate(array $cities, DateTimeImmutable $dateTimeImmutable): array;
}
