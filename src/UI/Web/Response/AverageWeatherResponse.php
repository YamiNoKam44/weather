<?php
declare(strict_types=1);

namespace App\UI\Web\Response;

use JsonSerializable;

readonly class AverageWeatherResponse implements JsonSerializable
{
    public function __construct(private float $averageTemperature)
    {
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'average_temp' => $this->averageTemperature,
        ];
    }
}
