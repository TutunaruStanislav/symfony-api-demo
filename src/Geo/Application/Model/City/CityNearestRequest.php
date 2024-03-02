<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\City;

use Symfony\Component\Validator\Constraints\NotNull;

final class CityNearestRequest
{
    #[NotNull]
    private float $lat;

    #[NotNull]
    private float $lng;

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
