<?php

namespace App\Controller\Api\v1;

use App\Manager\CourseManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/course')]
class CourseController
{
    public function __construct(
        private readonly CourseManager $courseManager,
    ) {}

    #[Route('/', methods: ['GET'])]
    public function getCourses(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;
        $user = $request->query->get('user');
        if (!$user) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($this->courseManager->getCoursesByUser($user, $page, $perPage));
    }
}
