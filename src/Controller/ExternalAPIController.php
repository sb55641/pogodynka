<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ExternalAPIType;

final class ExternalAPIController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ExternalAPIType::class);
        $weatherData = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $data['latitude'], $data['longitude']
            );
            $weatherData = json_decode(file_get_contents($apiUrl), true);
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form,
            'weather_data' => $weatherData,
        ]);
    }
}
