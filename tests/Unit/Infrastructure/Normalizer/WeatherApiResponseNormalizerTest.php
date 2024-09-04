<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Normalizer;

use App\Infrastructure\Exception\InvalidArgumentWeatherApiResponseException;
use App\Infrastructure\Normalizer\WeatherApiResponseNormalizer;
use App\Tests\Unit\Infrastructure\Stub\WeatherResponseStub;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

class WeatherApiResponseNormalizerTest extends TestCase
{
    public function testIsClassInstanceAble(): void
    {
        $this->assertInstanceOf(WeatherApiResponseNormalizer::class , new WeatherApiResponseNormalizer());
    }

    #[DataProvider('dataProvider')]
    public function testNormalizerValidation(string $data, string $exceptionMessage): void
    {
        $normalizer = new WeatherApiResponseNormalizer();

        $this->expectException(InvalidArgumentWeatherApiResponseException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $normalizer->normalize(new WeatherResponseStub($data));
    }

    public function testNormalizerReturnTemperature(): void
    {
        $normalizer = new WeatherApiResponseNormalizer();
        $mockResponse = new WeatherResponseStub(json_encode([
            'forecast'=> [
                'forecastday' => [
                    0 => [
                        'day' => [
                            'avgtemp_c' => 21.3
                        ]
                    ]
                ]
            ]
        ]));

        $normalizedData = $normalizer->normalize($mockResponse);

        $this->assertEquals(21.3, $normalizedData);
    }

    public static function dataProvider(): array
    {
        return [
            [
                json_encode([
                    'forecast' => [
                        'forecastday' => [
                            0 => [
                                'day' => [
                                    'avgtemp_c' => 'test'
                                ]
                            ]
                        ]
                    ]
                ]),
                'Average temperature must be a numeric value'
            ],
            [
                json_encode([
                    'forecast' => [
                        'forecastday' => [
                            0 => [
                                'day' => []
                            ]
                        ]
                    ]
                ]),
                'Invalid Api Response structure'
            ],
            [
                json_encode([
                    'forecast' => [
                        'forecastday' => [
                            0 => []
                        ]
                    ]
                ]),
                'Invalid Api Response structure'
            ],
            [
                json_encode([
                    'forecast' => [
                        'forecastday' => []
                    ]
                ]),
                'Invalid Api Response structure'
            ],
            [
                json_encode([
                    'forecast' => []
                ]),
                'Invalid Api Response structure'
            ],
            [
                json_encode([]),
                'Invalid Api Response structure'
            ],
        ];
    }
}
