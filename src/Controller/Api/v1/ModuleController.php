<?php

namespace App\Controller\Api\v1;

use App\Manager\ModuleManager;
use App\Manager\SkillManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/modules')]
class ModuleController
{
    public function __construct(
        private readonly ModuleManager $moduleManager
    ) {}

    #[Route('', name: 'get_modules', methods: ['GET'])]
    public function getModules(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;
        $course = $request->query->get('course');
        if (!$course) {
            return new JsonResponse([], 404);
        }

        dd($this->moduleManager->getModulesByCourse($course, $page, $perPage));

        return new JsonResponse($this->moduleManager->getModulesByCourse($course, $page, $perPage));
    }
}
