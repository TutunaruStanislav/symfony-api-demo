<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\City;

use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CityRequest
{
    private int $offset = 0;

    private int $limit = 40;

    private string $search = '';

    #[Callback([self::class, 'validateCountryCode'])]
    private string $countryCode = '';

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public static function validateCountryCode(mixed $value, ExecutionContextInterface $context): ExecutionContextInterface
    {
        if (!empty($value)) {
            if (3 !== strlen($value)) {
                $context->buildViolation('Country code must be contains only 3 symbols')->addViolation();
            }
            if (!preg_match('/[A-Z]{3}/', $value)) {
                $context->buildViolation('Invalid country code format')->addViolation();
            }
        }

        return $context;
    }
}
