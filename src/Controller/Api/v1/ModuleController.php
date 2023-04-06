<?php

namespace App\Controller\Api\v1;

use App\Manager\ModuleManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/courses/{courseId}/modules')]
class ModuleController
{
    public function __construct(
        private readonly ModuleManager $moduleManager
    ) {}

    #[Route('', name: 'get_modules', methods: ['GET'])]
    public function getModules(Request $request, int $courseId): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->moduleManager->getModulesByCourse($courseId, $page, $perPage));
    }

    #[Route('', name: 'store_module', methods: ['POST'])]
    public function storeModule(Request $request, int $courseId): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $skillId = $this->moduleManager->storeModule($courseId, $body['title']);
        if (!$skillId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'moduleId' => $skillId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_module', methods: ['PUT'])]
    public function updateModule(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->moduleManager->updateModule($id, $body['title']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
