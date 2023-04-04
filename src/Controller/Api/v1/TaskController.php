<?php

namespace App\Controller\Api\v1;

use App\Manager\TaskManager;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/lessons/{lessonId}/tasks')]
class TaskController
{
    public function __construct(
        private readonly TaskManager $taskManager
    ) {}

    #[Route('', name: 'get_tasks', methods: ['GET'])]
    public function getTasks(Request $request, int $lessonId): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->taskManager->getTasks($lessonId, $page, $perPage));
    }
}
