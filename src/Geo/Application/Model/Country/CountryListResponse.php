<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\Country;

final class CountryListResponse
{
    /**
     * @var CountryListItem[]
     */
    private array $countries;

    /**
     * @param CountryListItem[] $countries
     */
    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return CountryListItem[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }
}
