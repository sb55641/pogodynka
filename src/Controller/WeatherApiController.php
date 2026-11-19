<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $util,
        LocationRepository $locationRepository,
        #[MapQueryParameter] string $format,
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {

        $location = $locationRepository->findOneByCityAndCountry($city, $country);
        $measurements = $util->getWeatherForLocation($location);

        if ($twig) {
            if ($format === 'csv') {
                $template = 'weather_api/index.csv.twig';
                $contentType = 'text/plain';
            } else {
                $template = 'weather_api/index.json.twig';
                $contentType = 'application/json';
            }

            $response = $this->render($template, [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
            $response->headers->set('Content-Type', $contentType);

            return $response;
        }

        if ($format === 'csv') {
            $lines = ['city,country,date,celsius'];
            foreach ($measurements as $m) {
                $lines[] = sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTempCelsius(),
                    $m->getFahrenheit(),
                );
            }

            return new Response(implode("\n", $lines), 200, [
                'Content-Type' => 'text/plain',
            ]);
        }
        return $this->json([
            'country' => $country,
            'city' => $city,
            'measurements' => array_map(fn($m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getTempCelsius(),
                'feels_like' => $m->getFeelsLikeCelsius(),
                'condition' => $m->getWeatherCondition(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
