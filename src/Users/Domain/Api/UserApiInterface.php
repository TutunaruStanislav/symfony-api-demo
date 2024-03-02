<?php

declare(strict_types=1);

namespace App\Users\Domain\Api;

interface UserApiInterface
{
    public function getUserData(string $username): string;
}
