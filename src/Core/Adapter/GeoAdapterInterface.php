<?php

declare(strict_types=1);

namespace App\Core\Adapter;

use App\Core\Model\Country;

interface GeoAdapterInterface
{
    /**
     * @return array<Country>
     */
    public function getCountriesForAuth(): array;
}
