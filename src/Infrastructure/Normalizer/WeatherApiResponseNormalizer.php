<?php
declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use App\Infrastructure\Exception\InvalidArgumentWeatherApiResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherApiResponseNormalizer
{
    public function normalize(ResponseInterface $response)
    {
        $response = $response->toArray();
        $this->validate($response);

        return $response['forecast']['forecastday'][0]['day']['avgtemp_c'];
    }

    private function validate(array $response): void
    {
        if (
            !isset($response['forecast']) ||
            !isset($response['forecast']['forecastday']) ||
            !isset($response['forecast']['forecastday'][0]) ||
            !isset($response['forecast']['forecastday'][0]['day']) ||
            !isset($response['forecast']['forecastday'][0]['day']['avgtemp_c'])
        ) {
            throw new InvalidArgumentWeatherApiResponseException('Invalid Api Response structure');
        }

        $averageTemp = $response['forecast']['forecastday'][0]['day']['avgtemp_c'];

        if (!$averageTemp || !is_numeric($averageTemp)) {
            throw new InvalidArgumentWeatherApiResponseException('Average temperature must be a numeric value');
        }
    }
}
