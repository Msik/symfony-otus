<?php

namespace App\Controller\Api\v1;

use App\Manager\SkillManager;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/skills')]
class SkillController
{
    public function __construct(
        private readonly SkillManager $skillManager
    ) {}

    #[Route('', name: 'get_skills', methods: ['GET'])]
    public function getSkills(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->skillManager->getSkills($page, $perPage));
    }

    #[Route('', name: 'store_skill', methods: ['POST'])]
    public function storeSkill(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $skillId = $this->skillManager->storeSkill($body['title']);
        if (!$skillId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'skillId' => $skillId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_skill', methods: ['PUT'])]
    public function updateTask(Request $request, int $id): Response
    {
        $body = json_decode($request->getContent(), true);
        if (!$body || !$body['title']) {
            return new JsonResponse(['message' => 'wrong payload'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->skillManager->updateSkill($id, $body['title']);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
