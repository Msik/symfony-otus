<?php

namespace App\Controller\Api\GetTaskPoints\v1;

use App\Dto\ManageGetPointDto;
use App\Entity\User;
use App\Service\PointsCacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly PointsCacheService $pointsService
    ) {}

    #[Route(path: '/api/v1/tasks-points', methods: ['GET'])]
    public function getPointsAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $getPointDto = ManageGetPointDto::fromRequest($request);

        return new JsonResponse(
            ['result' => $this->pointsService->getPointsByDto($user->getId(), $getPointDto)],
            Response::HTTP_OK
        );
    }
}
