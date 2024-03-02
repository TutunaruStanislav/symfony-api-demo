<?php

declare(strict_types=1);

namespace App\Geo\Domain\Repository;

interface CountryRepositoryInterface
{
    public function getList(string $search, int $offset, int $limit): mixed;

    public function getIsoByAlpha(string $alpha): string;

    public function getAll(): mixed;
}
