<?php

declare(strict_types=1);

namespace App\Geo\Application\Mapper;

use App\Geo\Application\Model\City\CityListItem;
use App\Geo\Domain\Entity\City;

class CityMapper
{
    public static function map(?City $city, CityListItem $item): ?CityListItem
    {
        if (null === $city) {
            return null;
        }

        return $item
            ->setCode($city->getCode())
            ->setTitle($city->getTitle())
            ->setLatitude($city->getLatitude())
            ->setLongitude($city->getLongitude());
    }
}
