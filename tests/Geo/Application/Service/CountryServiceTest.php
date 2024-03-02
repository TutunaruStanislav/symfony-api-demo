<?php

declare(strict_types=1);

namespace App\Tests\Geo\Application\Service;

use App\Geo\Application\Model\Country\CountryListItem;
use App\Geo\Application\Model\Country\CountryListResponse;
use App\Geo\Application\Model\Country\CountryRequest;
use App\Geo\Application\Service\CountryService;
use App\Geo\Domain\Entity\Country;
use App\Geo\Infrastructure\Repository\CountryRepository;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;

class CountryServiceTest extends AbstractTestCase
{
    private CountryRepository $countryRepository;

    /**
     * @var array<Country>
     */
    private array $countriesList;

    private const DEFAULT_COUNT_ITEMS = 2;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->countryRepository = $this->createMock(CountryRepository::class);
        $this->countriesList = $this->createCountriesList();

        parent::setUp();
    }

    public function testGetList(): void
    {
        $request = (new CountryRequest())
            ->setOffset(0)
            ->setLimit(2)
            ->setSearch('');

        /* @phpstan-ignore-next-line */
        $this->countryRepository->expects($this->once())
            ->method('getList')
            ->with($request->getSearch(), $request->getOffset(), $request->getLimit())
            ->willReturn($this->countriesList);

        $service = new CountryService($this->countryRepository);
        $expected = (new CountryListResponse(array_map([$this, 'map'], $this->countriesList)));

        $this->assertEquals($expected, $service->getList($request));
    }

    public function testGetCountriesForAuth(): void
    {
        /* @phpstan-ignore-next-line */
        $this->countryRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($this->countriesList);

        $service = new CountryService($this->countryRepository);
        $expected = new CountryListResponse(array_map(function ($country) {
            return (new CountryListItem())
                ->setIsoCode($country->getCodeIso())
                ->setPhoneMask($country->getPhoneMask())
                ->setPhoneLength($country->getPhoneLength());
        }, $this->countriesList));

        $this->assertEquals($expected, $service->getCountriesForAuth());
    }

    /**
     * @return array<Country>
     *
     * @throws \ReflectionException
     */
    private function createCountriesList(): array
    {
        $list = [];
        for ($i = 0; $i < self::DEFAULT_COUNT_ITEMS; ++$i) {
            $entity = (new Country())
                ->setTitle('Armenia')
                ->setCodeIso('051')
                ->setCodeAlpha('ARM')
                ->setPhoneLength(1)
                ->setPhoneMask('374');

            $this->setEntityId($entity, $i);

            $list[] = $entity;
        }

        return $list;
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
