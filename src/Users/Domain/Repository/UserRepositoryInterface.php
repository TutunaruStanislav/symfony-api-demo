<?php

declare(strict_types=1);

namespace App\Users\Domain\Repository;

interface UserRepositoryInterface
{
    public function getOne(string $username): mixed;
}
