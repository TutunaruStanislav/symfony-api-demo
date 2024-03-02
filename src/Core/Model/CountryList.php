<?php

declare(strict_types=1);

namespace App\Core\Model;

final class CountryList
{
    /**
     * @var Country[]
     */
    private array $countries;

    /**
     * @param Country[] $countries
     */
    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }
}
