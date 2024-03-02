<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\Service;

use App\Tests\AbstractTestCase;
use App\Users\Application\Exception\UserNotFoundException;
use App\Users\Application\Model\User\UserModel;
use App\Users\Application\Service\UserService;
use App\Users\Domain\Entity\User;
use App\Users\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;

class UserServiceTest extends AbstractTestCase
{
    private UserRepository $userRepository;

    private string $defaultUsername = '3740000000000';

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);

        parent::setUp();
    }

    public function testGetUserDataAndUserNotFound(): void
    {
        /* @phpstan-ignore-next-line */
        $this->userRepository->expects($this->once())
            ->method('getOne')
            ->with($this->defaultUsername)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        (new UserService($this->userRepository))->getUserData($this->defaultUsername);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetUserData(): void
    {
        $user = (new User())
            ->setUsername($this->defaultUsername)
            ->setEmail('test@mail.com')
            ->setName('test_user');
        $this->setEntityId($user, 1);

        /* @phpstan-ignore-next-line */
        $this->userRepository->expects($this->once())
            ->method('getOne')
            ->with($this->defaultUsername)
            ->willReturn($user);

        $service = new UserService($this->userRepository);
        $expected = (new UserModel())
            ->setId((string) $user->getId())
            ->setUsername($user->getUsername())
            ->setEmail($user->getEmail())
            ->setName($user->getName());

        $this->assertEquals($expected, $service->getUserData($this->defaultUsername));
    }
}
