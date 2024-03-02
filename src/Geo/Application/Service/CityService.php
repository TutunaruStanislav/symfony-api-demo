<?php

declare(strict_types=1);

namespace App\Geo\Application\Service;

use App\Geo\Application\Mapper\CityMapper;
use App\Geo\Application\Model\City\CityListItem;
use App\Geo\Application\Model\City\CityListResponse;
use App\Geo\Application\Model\City\CityNearestRequest;
use App\Geo\Application\Model\City\CityRequest;
use App\Geo\Domain\Entity\City;
use App\Geo\Domain\Repository\CityRepositoryInterface;
use App\Geo\Domain\Repository\CountryRepositoryInterface;

readonly class CityService
{
    public function __construct(
        private CityRepositoryInterface $cityRepository,
        private CountryRepositoryInterface $countryRepository
    ) {
    }

    public function getList(CityRequest $request): CityListResponse
    {
        $isoCode = $request->getCountryCode() ? $this->countryRepository->getIsoByAlpha($request->getCountryCode()) : '';
        $total = $this->cityRepository->getTotal($request->getSearch(), $isoCode);
        $list = $this->cityRepository->getList($request->getOffset(), $request->getLimit(), $request->getSearch(),
            $isoCode);

        return new CityListResponse(array_map(
            fn (City $city) => CityMapper::map($city, new CityListItem()), $list
        ), $total);
    }

    public function getNearestCity(CityNearestRequest $request): ?CityListItem
    {
        return CityMapper::map($this->cityRepository->getNearest($request->getLat(), $request->getLng()),
            new CityListItem());
    }
}
