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
}
