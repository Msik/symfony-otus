<?php

namespace App\Manager;

use App\Dto\ManageUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getAllUsersForChoice(): array
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        /** @var User[] $users */
        $users = $repository->findAll();

        $result = [];
        foreach ($users as $user) {
            $result[$user->getPhone()] = $user->getId();
        }

        return $result;
    }

    public function getUsers(int $page, int $perPage): array
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        $paginator = $repository->getUsers($page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'users' => array_map(static fn (User $user) => $user->toArray(), (array)$paginator->getIterator()),
        ];
    }

    public function storeUser(string $phone): ?int
    {
        $user = new User();
        $user->setPhone($phone);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    public function updateUser(int $userId, string $phone): ?User
    {
        $user = $this->getUserById($userId);
        if (!$user) {
            return null;
        }

        $user->setPhone($phone);
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUserById(int $userId): bool
    {
        $user = $this->getUserById($userId);
        if (!$user) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }

    public function getUserById(int $id): ?User
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->find($id);
    }

    public function saveUserFromDto(User $user, ManageUserDto $manageUserDto): ?int
    {
        $user->setPhone($manageUserDto->phone);
        $user->setRoles($manageUserDto->roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    public function getUserByPhone(string $phone): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $user */
        $user = $userRepository->findOneBy(['phone' => $phone]);

        return $user;
    }

    public function updateUserToken(string $phone): ?string
    {
        $user = $this->getUserByPhone($phone);
        if (!$user) {
            return null;
        }

        $token = base64_encode(random_bytes(20));
        $user->setToken($token);
        $this->entityManager->flush();

        return $token;
    }

    public function getUserByToken(string $token): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $user */
        $user = $userRepository->findOneBy(['token' => $token]);

        return $user;
    }
}
