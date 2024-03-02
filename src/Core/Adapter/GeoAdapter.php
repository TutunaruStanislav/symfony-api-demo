<?php

declare(strict_types=1);

namespace App\Core\Adapter;

use App\Core\Model\Country;
use App\Core\Model\CountryList;
use App\Geo\Domain\Api\GeoApiInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class GeoAdapter implements GeoAdapterInterface
{
    public function __construct(
        private GeoApiInterface $geoApi,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @return array<Country>
     */
    public function getCountriesForAuth(): array
    {
        $result = $this->serializer->deserialize($this->geoApi->getCountriesForAuth(), CountryList::class, 'json');

        return !empty($result->getCountries()) ? $result->getCountries() : [];
    }
}
