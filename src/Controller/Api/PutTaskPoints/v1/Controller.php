<?php

namespace App\Controller\Api\PutTaskPoints\v1;

use App\Entity\User;
use App\Dto\ManageTaskPointDto;
use App\Service\PointsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly PointsService $pointsService,
    ) {}

    #[Route(path: '/api/v1/put-tasks-points', methods: ['POST'])]
    public function putPointsAction(Request $request): Response
    {
        $taskPointDto = ManageTaskPointDto::fromRequest($request);
        /** @var User $user */
        $user = $this->getUser();

        $result = $this->pointsService->putByDto($user, $taskPointDto);

        return new JsonResponse(['success' => $result]);
    }
}
