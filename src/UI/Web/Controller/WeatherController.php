<?php
declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Application\Service\WeatherService;
use App\UI\Web\Request\AverageTemperatureRequest;
use App\UI\Web\Response\AverageWeatherResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    public function __construct(private readonly WeatherService $weatherService)
    {
    }

    #[Route('/average_temp', name: 'average_temperature', methods: [Request::METHOD_POST])]
    public function averageTemperature(Request $request): JsonResponse
    {
        $request = new AverageTemperatureRequest($request);

        $averageTemperature = $this->weatherService->calculateWeatherTemperatureByCitiesAndDate($request);

        $response = new AverageWeatherResponse($averageTemperature);

        return new JsonResponse($response);
    }
}
