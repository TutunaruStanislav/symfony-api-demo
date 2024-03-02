<?php

declare(strict_types=1);

namespace App\Users\Application\Service;

use App\Users\Application\Exception\UserNotFoundException;
use App\Users\Application\Model\User\UserModel;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserRepositoryInterface;

readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function getUserData(string $username): UserModel
    {
        /** @var User $user */
        $user = $this->userRepository->getOne($username);
        if (!$user) {
            throw new UserNotFoundException(sprintf('user not found: %s', $username), description: 'User not found');
        }

        return (new UserModel())
            ->setId((string) $user->getId())
            ->setUsername($user->getUsername())
            ->setEmail($user->getEmail())
            ->setName($user->getName());
    }
}
