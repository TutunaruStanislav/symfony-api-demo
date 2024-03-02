<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Users\Application\Model\User\UserModel;
use App\Users\Application\Service\UserService;
use App\Users\Domain\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route(path: '/api/v1/user', name: 'get_user_info', methods: ['GET'])]
    #[OA\Parameter(name: 'X-Access-Token', description: 'Access token', in: 'header', required: true, schema: new OA\Schema(type: 'string', default: '3dbe06033befda5cc41c5fa2197efcb6'))]
    #[Security(name: 'bearerAuth')]
    #[OA\Response(response: 200, description: 'OK', content: new Model(type: UserModel::class))]
    #[OA\Response(response: 401, description: 'Authorization error')]
    #[OA\Response(response: 403, description: 'Common error')]
    public function getUserInfo(#[CurrentUser] User $user): Response
    {
        return $this->json($this->userService->getUserData($user->getUserIdentifier()));
    }
}
