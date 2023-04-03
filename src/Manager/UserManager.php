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
}
