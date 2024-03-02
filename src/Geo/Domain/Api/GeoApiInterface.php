<?php

declare(strict_types=1);

namespace App\Geo\Domain\Api;

interface GeoApiInterface
{
    public function getCountriesForAuth(): string;
}
