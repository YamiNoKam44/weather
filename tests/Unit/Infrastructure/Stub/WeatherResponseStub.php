<?php
declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Stub;

use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherResponseStub implements ResponseInterface
{
    public function __construct(private string $content)
    {
    }

    public function getStatusCode(): int
    {
        // TODO: Implement getStatusCode() method.
    }

    public function getHeaders(bool $throw = true): array
    {
        // TODO: Implement getHeaders() method.
    }

    public function getContent(bool $throw = true): string
    {
        // TODO: Implement getContent() method.
    }

    public function toArray(bool $throw = true): array
    {
        return json_decode($this->content, $throw);
    }

    public function cancel(): void
    {
        // TODO: Implement cancel() method.
    }

    public function getInfo(?string $type = null)
    {
        // TODO: Implement getInfo() method.
    }
}
