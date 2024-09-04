<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Client;

use App\Infrastructure\Client\WeatherApiClient;
use App\Infrastructure\Exception\WeatherApiException;
use App\Infrastructure\Normalizer\WeatherApiResponseNormalizer;
use App\Infrastructure\Service\HttpWeatherApiClient;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class WeatherApiClientTest extends TestCase
{
    private readonly HttpWeatherApiClient $httpWeatherApiClient;
    private readonly WeatherApiResponseNormalizer $weatherApiResponseNormalizer;

    protected function setUp(): void
    {
        $this->httpWeatherApiClient = new HttpWeatherApiClient(new MockHttpClient(new MockResponse()), 'key');
        $this->weatherApiResponseNormalizer = $this->createMock(WeatherApiResponseNormalizer::class);
    }

    public function testIsInstanceAble(): void
    {
        $this->assertInstanceOf(WeatherApiClient::class, new WeatherApiClient($this->httpWeatherApiClient, $this->weatherApiResponseNormalizer));
    }

    #[DataProvider('dataProvider')]
    public function testClientWillThrowEmptyBodyException(array $parameters, string $errorMessage): void
    {
        $client = new WeatherApiClient(new HttpWeatherApiClient(new MockHttpClient(new MockResponse($parameters)), 'key'), new WeatherApiResponseNormalizer());

        $this->expectException(WeatherApiException::class);
        $this->expectExceptionMessage($errorMessage);

        $client->getWeatherByCitiesAndDate(['gdansk', 'warszawa'],
            DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-01'));
    }

    public static function dataProvider(): array
    {
        return [
            [
                [],
                'Response body is empty.'
            ],
            [
                [20, 30, 40],
                'JSON content was expected to decode to an array, "int" returned for "http://api.weatherapi.com/v1/history.json?key=key&q=gdansk&dt=2020-01-01".'
            ],
        ];
    }

    public function testCorrectResponse(): void
    {
        $this->weatherApiResponseNormalizer->expects($this->any())->method('normalize')->willReturn(21.3);
        $client = new WeatherApiClient(new HttpWeatherApiClient(new MockHttpClient([new MockResponse([json_encode([
            'forecast' => [
                'forecastday' => [
                    0 => [
                        'day' => [
                            'avgtemp_c' => 21.3
                        ]
                    ]
                ]
            ]
        ])]),
            new MockResponse([json_encode([
                'forecast' => [
                    'forecastday' => [
                        0 => [
                            'day' => [
                                'avgtemp_c' => 21.3
                            ]
                        ]
                    ]
                ]
            ])])]), 'test'), $this->weatherApiResponseNormalizer);

        $weather = $client->getWeatherByCitiesAndDate(['gdansk', 'warszawa'],
            DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-01'));

        $this->assertEquals($weather, [21.3, 21.3]);
    }
}
