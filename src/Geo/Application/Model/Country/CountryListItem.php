<?php

declare(strict_types=1);

namespace App\Geo\Application\Model\Country;

final class CountryListItem
{
    private string $countryName;

    private string $isoCode;

    private string $phoneMask;

    private int $phoneLength;

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }

    public function getPhoneMask(): string
    {
        return $this->phoneMask;
    }

    public function setPhoneMask(string $phoneMask): self
    {
        $this->phoneMask = $phoneMask;

        return $this;
    }

    public function getPhoneLength(): int
    {
        return $this->phoneLength;
    }

    public function setPhoneLength(int $phoneLength): self
    {
        $this->phoneLength = $phoneLength;

        return $this;
    }
}
