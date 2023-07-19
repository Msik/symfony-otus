<?php

namespace App\Controller\Api\GetTaskPoints\v1;

use App\Achievement\FivePerfect;
use App\Dto\ManageGetPointDto;
use App\Entity\User;
use App\Manager\UserPointManager;
use App\Service\AchievementService;
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
        private readonly UserPointManager $userPointManager,
        private readonly AchievementService $achievementService
    ) {}

    #[Route(path: '/api/v1/tasks-points', methods: ['GET'])]
    public function getPointsAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        dd($this->achievementService->checkAchievements($user));
        $getPointDto = ManageGetPointDto::fromRequest($request);

        return new JsonResponse(
            ['result' => $this->userPointManager->getPointsByDto($user->getId(), $getPointDto)],
            Response::HTTP_OK
        );
    }
}
