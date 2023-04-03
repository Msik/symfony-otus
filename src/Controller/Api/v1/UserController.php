<?php

namespace App\Controller\Api\v1;

use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/users')]
class UserController
{
    public function __construct(
        private readonly UserManager $userManager
    ) {}

    #[Route('', name: 'get_users', methods: ['GET'])]
    public function getUsers(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->userManager->getUsers($page, $perPage));
    }
}
