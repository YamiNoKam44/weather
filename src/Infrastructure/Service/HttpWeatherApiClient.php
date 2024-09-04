<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpWeatherApiClient
{
    private const string BASE_URI = 'http://api.weatherapi.com/v1/history.json';
    private const string BASE_PATTERN = '?key=%s&q=%s&dt=%s';
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string              $weatherApiKey,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function fetchWeather(string $city, string $data): ResponseInterface
    {
        return $this->httpClient->request(Request::METHOD_GET,
            sprintf('%s%s', self::BASE_URI,
                sprintf(self::BASE_PATTERN, $this->weatherApiKey, $city, $data)
            ));
    }
}
