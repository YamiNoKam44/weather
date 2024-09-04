<?php
declare(strict_types=1);

namespace App\UI\Web\Request;

use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

readonly class AverageTemperatureRequest
{
    private array $cities;
    private ?string $date;
    public function __construct(Request $request)
    {
        $content = $request->getContent();

        $data = json_decode($content, true);

        $this->cities = $data['cities'] ?? [];
        $this->date = $data['date'] ?? null;

        $this->validate();
    }

    public function getCities(): array
    {
        return $this->cities;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    private function validate(): void
    {
        if ($this->cities === [] || $this->date === null ||
            DateTimeImmutable::createFromFormat('Y-m-d', $this->date) === false) {
            throw new InvalidArgumentException('Invalid input data');
        }

        foreach ($this->cities as $city) {
            if(mb_strlen($city) < 2) {
                throw new InvalidArgumentException('Invalid input data');
            }
        }
    }
}
