<?php

declare(strict_types=1);

namespace App\Tests\Geo\Application\Service;

use App\Geo\Application\Mapper\CityMapper;
use App\Geo\Application\Model\City\CityListItem;
use App\Geo\Application\Model\City\CityListResponse;
use App\Geo\Application\Model\City\CityNearestRequest;
use App\Geo\Application\Model\City\CityRequest;
use App\Geo\Application\Service\CityService;
use App\Geo\Domain\Entity\City;
use App\Geo\Infrastructure\Repository\CityRepository;
use App\Geo\Infrastructure\Repository\CountryRepository;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;

class CityServiceTest extends AbstractTestCase
{
    private CityRepository $cityRepository;

    private CountryRepository $countryRepository;

    private const ISO_CODE = '398';

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cityRepository = $this->createMock(CityRepository::class);
        $this->countryRepository = $this->createMock(CountryRepository::class);

        parent::setUp();
    }

    public function testGetList(): void
    {
        $request = (new CityRequest())
            ->setOffset(0)
            ->setLimit(20)
            ->setSearch('')
            ->setCountryCode('KAZ');

        $citiesList = [
            $this->createCityEntity('Astana', 'K01', self::ISO_CODE),
            $this->createCityEntity('Almaty', 'K02', self::ISO_CODE),
        ];

        /* @phpstan-ignore-next-line */
        $this->countryRepository->expects($this->once())
            ->method('getIsoByAlpha')
            ->with($request->getCountryCode())
            ->willReturn(self::ISO_CODE);

        /* @phpstan-ignore-next-line */
        $this->cityRepository->expects($this->once())
            ->method('getTotal')
            ->with($request->getSearch(), self::ISO_CODE)
            ->willReturn(2);

        /* @phpstan-ignore-next-line */
        $this->cityRepository->expects($this->once())
            ->method('getList')
            ->with($request->getOffset(), $request->getLimit(), $request->getSearch(), self::ISO_CODE)
            ->willReturn($citiesList);

        $service = new CityService($this->cityRepository, $this->countryRepository);
        $expected = (new CityListResponse(array_map(
            fn (City $city) => CityMapper::map($city, new CityListItem()), $citiesList
        ), 2));

        $this->assertEquals($expected, $service->getList($request));
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetNearestCities(): void
    {
        $request = (new CityNearestRequest())
            ->setLat(44.4)
            ->setLng(37.2);

        $city = $this->createCityEntity('Moscow', 'R01', '643');

        /* @phpstan-ignore-next-line */
        $this->cityRepository->expects($this->once())
            ->method('getNearest')
            ->with($request->getLat(), $request->getLng())
            ->willReturn($city);

        $service = new CityService($this->cityRepository, $this->countryRepository);
        $expected = (new CityListItem())
            ->setLatitude($city->getLatitude())
            ->setLongitude($city->getLongitude())
            ->setTitle($city->getTitle())
            ->setCode($city->getCode());

        $this->assertEquals($expected, $service->getNearestCity($request));
    }

    public function testGetNearestCitiesNotFound(): void
    {
        $request = (new CityNearestRequest())
            ->setLat(44.4)
            ->setLng(37.2);

        /* @phpstan-ignore-next-line */
        $this->cityRepository->expects($this->once())
            ->method('getNearest')
            ->with($request->getLat(), $request->getLng())
            ->willReturn(null);

        $service = new CityService($this->cityRepository, $this->countryRepository);

        $this->assertEquals(null, $service->getNearestCity($request));
    }

    /**
     * @throws \ReflectionException
     */
    private function createCityEntity(string $title, string $code, string $countryCode): City
    {
        $entity = (new City())
            ->setTitle($title)
            ->setCode($code)
            ->setCountryCode($countryCode)
            ->setLatitude(0.0)
            ->setLongitude(0.0)
            ->setTimezone(5);

        $this->setEntityId($entity, 1);

        return $entity;
    }
}
