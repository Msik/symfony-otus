<?php

namespace App\Repository;

use App\Entity\UserPoint;
use Doctrine\ORM\EntityRepository;

class UserPointRepository extends EntityRepository
{
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
