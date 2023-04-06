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

    #[Route('', name: 'store_task', methods: ['POST'])]
    public function storeTask(Request $request, int $lessonId): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $taskId = $this->taskManager->storeTask($lessonId, $body['title']);
        if (!$taskId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'taskId' => $taskId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_task', methods: ['PUT'])]
    public function updateTask(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->taskManager->updateTask($id, $body['title']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(int $id): Response
    {
        $result = $this->taskManager->deleteTaskById($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
