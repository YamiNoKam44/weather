<?php
declare(strict_types=1);

namespace App\Tests\Unit\UI\Web\Request;

use App\UI\Web\Request\AverageTemperatureRequest;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AverageTemperatureRequestTest extends TestCase
{

    #[DataProvider('dataProvider')]
    public function testAverageTemperatureRequestWillThrowException(array $data): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid input data');
        new AverageTemperatureRequest(new Request([],[],[],[],[],[], $data));
    }

    public function testAverageTemperatureRequestPassValidation(): void
    {
        $request = new AverageTemperatureRequest(
            new Request([],[],[],[],[],[], json_encode(['date' => '2137-01-01',
                'cities' => ['Koszalin', 'Gdańsk', 'Bydgoszcz']])));


        $this->assertInstanceOf(AverageTemperatureRequest::class, $request);
        $this->assertEquals('2137-01-01', $request->getDate());
        $this->assertEquals(['Koszalin', 'Gdańsk', 'Bydgoszcz'], $request->getCities());
    }

    public static function dataProvider(): array
    {
        [
            [
                json_encode(['test'])
            ],
            [
                json_encode(['date' => 'someday',])
            ],
            [
                json_encode(['date' => '2137-01-01',])
            ],
            [
                json_encode(['date' => '2137-01-01', 'cities' => ['jeden','da']])
            ],
        ];
    }
}
