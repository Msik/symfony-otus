<?php

namespace App\Manager;

use App\Entity\UserPoint;
use App\Repository\UserPointRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserPointManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getPointsByUser(int $userId, int $page, int $perPage): array
    {
        /** @var UserPointRepository $repository */
        $repository = $this->entityManager->getRepository(UserPoint::class);
        $paginator = $repository->getPointsByUser($userId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'points' => array_map(static fn (UserPoint $userPoint) => $userPoint->toArray(), (array)$paginator->getIterator()),
        ];
    }
}
