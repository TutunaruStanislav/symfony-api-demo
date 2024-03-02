<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\City;

final class CityListResponse
{
    /**
     * @var CityListItem[]
     */
    private array $items;

    private int $total;

    /**
     * @param CityListItem[] $items
     */
    public function __construct(array $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    /**
     * @return CityListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
