<?php

declare(strict_types=1);

namespace App\Core\Adapter;

use App\Core\Model\User;
use App\Users\Domain\Api\UserApiInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class UsersAdapter implements UsersAdapterInterface
{
    public function __construct(
        private UserApiInterface $userApi,
        private SerializerInterface $serializer
    ) {
    }

    public function getUserData(string $username): User
    {
        return $this->serializer->deserialize($this->userApi->getUserData($username), User::class, 'json');
    }
}
