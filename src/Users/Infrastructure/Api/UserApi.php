<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Api;

use App\Users\Application\Service\UserService;
use App\Users\Domain\Api\UserApiInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class UserApi implements UserApiInterface
{
    public function __construct(
        private UserService $userService,
        private SerializerInterface $serializer
    ) {
    }

    public function getUserData(string $username): string
    {
        return $this->serializer->serialize($this->userService->getUserData($username), 'json');
    }
}
