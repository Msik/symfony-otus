<?php

namespace App\Controller\Api\PutTaskPoints\v1;

use App\Entity\User;
use App\Dto\ManageTaskPointDto;
use App\Service\AsyncService;
use App\Service\PointsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly AsyncService $asyncService,
    ) {}

    #[Route(path: '/api/v1/put-tasks-points', methods: ['POST'])]
    public function putPointsAction(Request $request): Response
    {
        $taskPointDto = ManageTaskPointDto::fromRequest($request);
        /** @var User $user */
        $user = $this->getUser();

        $message = json_encode([
            'userId' => $user->getId(),
            'taskId' => $taskPointDto->taskId,
            'points' => $taskPointDto->points,
        ], JSON_THROW_ON_ERROR);
        $result = $this->asyncService->publishToExchange(AsyncService::PUT_TASK_POINTS, $message);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
