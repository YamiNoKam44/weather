<?php
declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Domain\Repository\WeatherRepositoryInterface;
use App\Infrastructure\Exception\WeatherApiException;
use App\Infrastructure\Normalizer\WeatherApiResponseNormalizer;
use App\Infrastructure\Service\HttpWeatherApiClient;
use DateTimeImmutable;
use Throwable;

readonly class WeatherApiClient implements WeatherRepositoryInterface
{
    public function __construct(
        private HttpWeatherApiClient $httpWeatherApiClient,
        private WeatherApiResponseNormalizer $responseNormalizer)
    {
    }

    /**
     * @throws WeatherApiException
     */
    public function getWeatherByCitiesAndDate(array $cities, DateTimeImmutable $dateTimeImmutable): array
    {
        $averageTemperature = [];
        try {
            foreach ($cities as $city) {
                $weatherApiResponse = $this->httpWeatherApiClient->fetchWeather($city,
                    $dateTimeImmutable->format('Y-m-d'));
                $averageTemperature[] = $this->responseNormalizer->normalize($weatherApiResponse);
            }
        } catch (Throwable $exception) {
            throw new WeatherApiException($exception->getMessage());
        }

        return $averageTemperature;
    }
}
