<?php

namespace App\Controller\Api\GetTaskPoints\v1;

use App\Entity\User;
use App\Manager\UserPointManager;
use App\Service\AsyncService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly AsyncService $asyncService,
        private readonly UserPointManager $userPointManager
    ) {}

    #[Route(path: '/api/v1/tasks-points', methods: ['GET'])]
    public function getPointsAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $taskId = $request->query->get('task') ?? null;
        $skillId = $request->query->get('skill') ?? null;

        return new JsonResponse(
            ['result' => $this->userPointManager->getPoints($user->getId(), $taskId, $skillId)],
            Response::HTTP_OK
        );
    }
}
