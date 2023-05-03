<?php

namespace App\Controller\Api\v1;

use App\Dto\ManageUserDto;
use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/v1/users')]
class UserController
{
    public function __construct(
        private readonly UserManager $userManager
    ) {}

    #[Route('', name: 'get_users', methods: ['GET'])]
    public function getUsers(Request $request): Response
    {
        $perPage = $request->query->get('perPage') ?? 5;
        $page = $request->query->get('page') ?? 1;

        return new JsonResponse($this->userManager->getUsers($page, $perPage));
    }

    #[Route('', name: 'store_user', methods: ['POST'])]
    public function storeUser(Request $request): Response
    {
        $saveUserDto = ManageUserDto::fromRequest($request);

        $userId = $this->userManager->saveUserFromDto(new User(), $saveUserDto);
        if (!$userId) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true, 'userId' => $userId]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'update_user', methods: ['PUT'])]
    public function updateUser(Request $request, int $id): Response
    {
        $user = $this->userManager->getUserById($id);
        if (!$user) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        $saveUserDto = ManageUserDto::fromRequest($request);
        $result = $this->userManager->saveUserFromDto($user, $saveUserDto);

        return new JsonResponse(['success' => (bool)$result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id): Response
    {
        $result = $this->userManager->deleteUserById($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}
