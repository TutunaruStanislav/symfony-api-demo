<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\Adapter\GeoAdapterInterface;
use App\Core\Exception\InvalidCredentialsException;
use App\Core\Exception\UserNotFoundException;
use App\Core\Exception\ValidationException;
use App\Core\Mapper\ViolationsMapper;
use App\Core\Model\JwtAuthRequest;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ValidatorInterface $validator,
        private GeoAdapterInterface $geoAdapter,
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack
    ) {
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $jwtAuthRequest = (new JwtAuthRequest($this->geoAdapter->getCountriesForAuth()))->setPhone($identifier);
        $errors = $this->validator->validate($jwtAuthRequest);
        if (count($errors)) {
            throw new ValidationException(name: 'Invalid data', description: 'Invalid input params provided', outputMessage: ViolationsMapper::map($errors));
        }

        $user = $this->getUser($jwtAuthRequest->getPhone());
        $requestParams = $this->requestStack->getCurrentRequest()->toArray();
        if (!empty($requestParams['password']) && !$this->passwordHasher->isPasswordValid($user, $requestParams['password'])) {
            throw new InvalidCredentialsException(description: 'Invalid credentials provided');
        }
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    /**
     * @param array<string> $payload
     */
    public function loadUserByIdentifierAndPayload(string $identifier, array $payload): ?UserInterface
    {
        return $this->getUser($identifier);
    }

    /**
     * @param array<string> $payload
     */
    public function loadUserByUsernameAndPayload(string $username, array $payload): ?UserInterface
    {
        return $this->getUser($username);
    }

    public function refreshUser(UserInterface $user): ?UserInterface
    {
        return null;
    }

    private function getUser(string $username): User
    {
        $user = $this->userRepository->getOne($username);
        if (null === $user) {
            throw new UserNotFoundException(sprintf('user not found: %s', $username), description: 'User not found');
        }

        return $user;
    }
}
