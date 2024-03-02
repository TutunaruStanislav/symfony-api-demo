<?php

declare(strict_types=1);

namespace App\Core\Model;

final class Country
{
    private string $phoneMask;

    private int $phoneLength;

    private string $isoCode;

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

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }
}
