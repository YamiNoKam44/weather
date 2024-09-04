<?php
declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\Service\WeatherService;
use App\Domain\Repository\WeatherRepositoryInterface;
use App\Domain\Service\WeatherCalculator;
use App\UI\Web\Request\AverageTemperatureRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class WeatherServiceTest extends TestCase
{
    /** @var WeatherRepositoryInterface|MockObject  */
    private readonly WeatherRepositoryInterface $weatherRepository;
    private readonly WeatherCalculator $weatherCalculator;

    protected function setUp(): void
    {
        $this->weatherRepository = $this->createMock(WeatherRepositoryInterface::class);
        $this->weatherCalculator = new WeatherCalculator();
    }

    public function testIsInstanceAble(): void
    {
        $this->assertInstanceOf(WeatherService::class, new WeatherService($this->weatherRepository, $this->weatherCalculator));
    }

    public function testServiceCalculation(): void
    {
        $this->weatherRepository->expects($this->once())->method('getWeatherByCitiesAndDate')->willReturn([10, 20, 30]);
        $service = new WeatherService($this->weatherRepository, $this->weatherCalculator);

        $calculatedWeather = $service->calculateWeatherTemperatureByCitiesAndDate(new AverageTemperatureRequest(
            new Request([],[],[],[],[],[], json_encode(['date' => '2137-01-01',
                'cities' => ['Koszalin', 'Gdańsk', 'Bydgoszcz']]))));

        $this->assertEquals(20, $calculatedWeather);
    }
}
