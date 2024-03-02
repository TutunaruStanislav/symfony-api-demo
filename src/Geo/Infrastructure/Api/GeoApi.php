<?php

declare(strict_types=1);

namespace App\Geo\Infrastructure\Api;

use App\Geo\Application\Service\CountryService;
use App\Geo\Domain\Api\GeoApiInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class GeoApi implements GeoApiInterface
{
    public function __construct(
        private CountryService $countryService,
        private SerializerInterface $serializer
    ) {
    }

    public function getCountriesForAuth(): string
    {
        return $this->serializer->serialize($this->countryService->getCountriesForAuth(), 'json');
    }
}
