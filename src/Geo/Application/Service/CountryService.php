<?php

declare(strict_types=1);

namespace App\Geo\Application\Service;

use App\Geo\Application\Model\Country\CountryListItem;
use App\Geo\Application\Model\Country\CountryListResponse;
use App\Geo\Application\Model\Country\CountryRequest;
use App\Geo\Domain\Entity\Country;
use App\Geo\Domain\Repository\CountryRepositoryInterface;

readonly class CountryService
{
    public function __construct(private CountryRepositoryInterface $countryRepository)
    {
    }

    public function getList(CountryRequest $request): CountryListResponse
    {
        return new CountryListResponse(
            array_map([$this, 'map'], $this->countryRepository->getList($request->getSearch(),
                $request->getOffset(), $request->getLimit()))
        );
    }

    public function getCountriesForAuth(): CountryListResponse
    {
        return new CountryListResponse(
            array_map(function (Country $item) {
                return (new CountryListItem())
                    ->setIsoCode($item->getCodeIso())
                    ->setPhoneMask($item->getPhoneMask())
                    ->setPhoneLength($item->getPhoneLength());
            }, $this->countryRepository->getAll())
        );
    }

    private function map(Country $country): CountryListItem
    {
        return (new CountryListItem())
            ->setCountryName($country->getTitle())
            ->setIsoCode($country->getCodeIso())
            ->setPhoneMask($country->getPhoneMask())
            ->setPhoneLength($country->getPhoneLength());
    }
}
