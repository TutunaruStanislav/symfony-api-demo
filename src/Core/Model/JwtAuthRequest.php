<?php

declare(strict_types=1);

namespace App\Core\Model;

use App\Core\Validator\Validator;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

final class JwtAuthRequest
{
    /**
     * @param array<Country> $countries
     */
    public function __construct(public readonly array $countries)
    {
    }

    #[NotBlank]
    #[Callback([Validator::class, 'validatePhone'])]
    private string $phone = '';

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
