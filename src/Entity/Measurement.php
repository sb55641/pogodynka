<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $temp_celsius = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $feels_like_celsius = null;

    #[ORM\Column(length: 255)]
    private ?string $weather_condition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTempCelsius(): ?string
    {
        return $this->temp_celsius;
    }

    public function setTempCelsius(?string $temp_celsius): static
    {
        $this->temp_celsius = $temp_celsius;

        return $this;
    }

    public function getFeelsLikeCelsius(): ?string
    {
        return $this->feels_like_celsius;
    }

    public function setFeelsLikeCelsius(?string $feels_like_celsius): static
    {
        $this->feels_like_celsius = $feels_like_celsius;

        return $this;
    }

    public function getWeatherCondition(): ?string
    {
        return $this->weather_condition;
    }

    public function setWeatherCondition(?string $weather_condition): static
    {
        $this->weather_condition = $weather_condition;

        return $this;
    }
}
