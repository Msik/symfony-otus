<?php

namespace App\Repository;

use App\Entity\UserPoint;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserPointRepository extends EntityRepository
{
    public function getPointsByUser(int $userId, int $page, int $perPage): Paginator
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(UserPoint::class, 't')
            ->andWhere($queryBuilder->expr()->eq('t.user', ':user'))
            ->setParameter(':user', $userId)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return new Paginator($queryBuilder);
    }

    /**
     * @return UserPoint[]
     */
    public function getByUserTask(int $userId, int $taskId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        return $queryBuilder->select('up')
            ->from(UserPoint::class, 'up')
            ->andWhere($queryBuilder->expr()->eq('up.user', ':userId'))
            ->andWhere($queryBuilder->expr()->eq('up.task', ':taskId'))
            ->setParameters([':userId' => $userId, ':taskId' => $taskId])
            ->getQuery()
            ->getResult();
    }
}
