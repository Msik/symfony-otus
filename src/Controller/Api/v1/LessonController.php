<?php

namespace App\Controller\Api\v1;

use App\Manager\LessonManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/courses/{courseId}/lessons')]
class LessonController
{
    public function __construct(
        private readonly LessonManager $lessonManager
    ) {}

    #[Route('', name: 'get_lessons', methods: ['GET'])]
    public function getLessons(Request $request, int $courseId): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->lessonManager->getLessonsByCourse($courseId, $page, $perPage));
    }

    #[Route('', name: 'store_lesson', methods: ['POST'])]
    public function storeLesson(Request $request, int $courseId): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !is_string($body['title'])) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $module = $body['moduleId'] ?? null;
        $lessondId = $this->lessonManager->storeLesson($courseId, $body['title'], $module);
        if (!$lessondId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'lessonId' => $lessondId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_lesson', methods: ['PUT'])]
    public function updateLesson(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !is_string($body['title'])) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->lessonManager->updateLesson($id, $body['title']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'delete_lesson', methods: ['DELETE'])]
    public function deleteLesson(int $id): Response
    {
        $result = $this->lessonManager->deleteLessonById($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
