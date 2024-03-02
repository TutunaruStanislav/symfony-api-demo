<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\City;

use Symfony\Component\Validator\Constraints\NotBlank;

final class CityListItem
{
    #[NotBlank]
    private string $code;

    #[NotBlank]
    private string $title;

    #[NotBlank]
    private float $latitude;

    #[NotBlank]
    private float $longitude;

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
}
