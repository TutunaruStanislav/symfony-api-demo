<?php

declare(strict_types=1);

namespace App\Core\Handler;

use App\Core\Adapter\UsersAdapterInterface;
use App\Core\Model\JwtAuthResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class JwtAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private EventDispatcherInterface $dispatcher,
        private UsersAdapterInterface $usersAdapter,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @throws \Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        return $this->handleAuthenticationSuccess($token->getUser(), $request);
    }

    /**
     * @throws \Exception
     */
    private function handleAuthenticationSuccess(UserInterface $user, Request $request): JsonResponse
    {
        $jwt = $this->jwtManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $event = new AuthenticationSuccessEvent(['token' => $jwt], $user, $response);
        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);
        $responseData = $event->getData();

        $responseData = $this->serializer->serialize(
            new JwtAuthResponse($responseData['token'], $responseData['refreshToken'],
                $this->usersAdapter->getUserData($user->getUserIdentifier())),
            JsonEncoder::FORMAT,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, [], true);
    }
}
