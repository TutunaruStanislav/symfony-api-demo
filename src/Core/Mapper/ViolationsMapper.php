<?php

declare(strict_types=1);

namespace App\Core\Mapper;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsMapper
{
    /* @phpstan-ignore-next-line */
    public static function map(ConstraintViolationListInterface $violationList): array
    {
        $list = [];
        foreach ($violationList as $item) {
            $list[] = [
                'field' => $item->getPropertyPath(),
                'description' => $item->getMessage(),
            ];
        }

        return $list;
    }
}
