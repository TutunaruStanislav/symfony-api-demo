<?php

declare(strict_types=1);

namespace App\Geo\Domain\Entity;

use App\Geo\Domain\Repository\CityRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepositoryInterface::class)]
#[ORM\Table('geo_city')]
#[ORM\Index(columns: ['code'], name: 'city_code_index')]
#[ORM\Index(columns: ['country_code'], name: 'city_country_code_index')]
#[ORM\Index(columns: ['latitude', 'longitude'], name: 'city_latitude_longitude_index')]
final class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => ''])]
    private string $code;

    #[ORM\Column(type: 'string', length: 100, options: ['default' => ''])]
    private string $title;

    #[ORM\Column(type: 'float')]
    private float $latitude;

    #[ORM\Column(type: 'float')]
    private float $longitude;

    #[ORM\Column(type: 'integer', length: 3, nullable: true)]
    private ?int $timezone;

    #[ORM\Column(type: 'string', length: 3, options: ['default' => ''])]
    private string $countryCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getTimezone(): ?int
    {
        return $this->timezone;
    }

    public function setTimezone(?int $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
