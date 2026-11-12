<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Get weather for a city',
)]
class WeatherCityCommand{
    public function __construct(
        private WeatherUtil $weatherUtil,
    ) {
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        $io->writeln(sprintf('Location: %s, %s', $country, $city));
        $io->table(['Date', 'Temperature', 'Feels like', 'Condition'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTempCelsius(),
            $m->getFeelsLikeCelsius(),
            $m->getWeatherCondition(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
