<?php

namespace App\Controller\Api\v1;

use App\Manager\UserPointManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/points')]
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

    #[Route('', name: 'store_user_point', methods: ['POST'])]
    public function storeUserPoint(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['userId']|| !$body['taskId']|| !$body['points']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $skillId = $body['skillId'] ?? null;
        $userPointId = $this->userPointManager->storeUserPoint(
            $body['userId'],
            $body['taskId'],
            $body['points'],
            $skillId
        );
        if (!$userPointId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'userPointId' => $userPointId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_user_point', methods: ['PUT'])]
    public function updateUserPoint(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['points']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->userPointManager->updateUserPoint($id, $body['points']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
