<?php

declare(strict_types=1);

namespace App\Core\Model;

final readonly class JwtAuthResponse
{
    public function __construct(private string $authToken, private string $refreshToken, private ?object $user)
    {
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }
}
