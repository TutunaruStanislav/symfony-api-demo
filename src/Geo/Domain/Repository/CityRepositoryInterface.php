<?php

declare(strict_types=1);

namespace App\Geo\Domain\Repository;

interface CityRepositoryInterface
{
    public function getList(int $offset, int $limit, string $search, string $country): mixed;

    public function getTotal(string $search, string $country): int;

    public function getNearest(float $lat, float $lng): mixed;
}
