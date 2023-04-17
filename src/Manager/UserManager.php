<?php

namespace App\Manager;

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
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $repository->find($userId);
        if (!$user) {
            return null;
        }

        $user->setPhone($phone);
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUserById(int $userId): bool
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $repository->find($userId);
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
}
