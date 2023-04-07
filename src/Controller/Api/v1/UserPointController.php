<?php

namespace App\Controller\Api\v1;

use App\Manager\UserPointManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/points/')]
class UserPointController
{
    public function __construct(
        private readonly UserPointManager $userPointManager
    ) {}

    #[Route('', name: 'get_user_points', methods: ['GET'])]
    public function getUserPoints(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;
        $userId = $request->query->get('userId');
        if (!$userId) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($this->userPointManager->getPointsByUser($userId, $page, $perPage));
    }
}
