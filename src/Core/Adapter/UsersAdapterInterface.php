<?php

declare(strict_types=1);

namespace App\Core\Adapter;

use App\Core\Model\User;

interface UsersAdapterInterface
{
    public function getUserData(string $username): User;
}
