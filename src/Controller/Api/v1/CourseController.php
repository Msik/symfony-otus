<?php

namespace App\Controller\Api\v1;

use App\Manager\CourseManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/courses')]
class CourseController
{
    public function __construct(
        private readonly CourseManager $courseManager,
    ) {}

    #[Route('', name: 'get_courses', methods: ['GET'])]
    public function getCourses(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;
        $user = $request->query->get('user');
        if (!$user) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->courseManager->getCoursesByUser($user, $page, $perPage));
    }

    #[Route('',name: 'store_course', methods: ['POST'])]
    public function storeCourse(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !is_string($body['title'])) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $title = $body['title'];
        $courseId = $this->courseManager->storeCourse($title);
        if (!$courseId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'courseId' => $courseId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_course', methods: ['PUT'])]
    public function updateCourse(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !is_string($body['title'])) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->courseManager->updateCourse($id, $body['title']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'delete_course', methods: ['DELETE'])]
    public function deleteCourse(int $id): Response
    {
        $result = $this->courseManager->deleteCourseById($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
