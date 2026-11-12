<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Get weather for a location ID',
)]
class   WeatherLocationCommand{

    public function __construct(
        private LocationRepository $locationRepository,
        private WeatherUtil $weatherUtil,
    ) {
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s', $location->getCityName()));
        $io->table(['Date', 'Temperature', 'Feels like', 'Condition'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTempCelsius(),
            $m->getFeelsLikeCelsius(),
            $m->getWeatherCondition(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
