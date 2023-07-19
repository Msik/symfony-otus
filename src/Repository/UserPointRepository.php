<?php

namespace App\Repository;

use App\Dto\ManageGetPointDto;
use App\Entity\UserPoint;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
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

    public function getPointsByDto(int $userId, ManageGetPointDto $getPointDto): int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('SUM(up.points)')
            ->from(UserPoint::class, 'up')
            ->andWhere($queryBuilder->expr()->eq('up.user', ':userId'))
            ->setParameter(':userId', $userId);

        if ($getPointDto->taskId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('up.task', ':taskId'))
                ->setParameter(':taskId', $getPointDto->taskId);
        }

        if ($getPointDto->skillId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('up.skill', ':skillId'))
                ->setParameter(':skillId', $getPointDto->skillId);
        }

        if ($getPointDto->courseId) {
            $queryBuilder->innerJoin('up.task', 't')
                ->innerJoin('t.lesson', 'l')
                ->andWhere($queryBuilder->expr()->eq('l.course', ':courseId'))
                ->setParameter(':courseId', $getPointDto->courseId);
        }

        if ($getPointDto->startDate) {
            $queryBuilder->andWhere($queryBuilder->expr()->gte('up.createdAt', ':startDate'))
                ->setParameter(':startDate', $getPointDto->startDate);
        }

        if ($getPointDto->endDate) {
            $queryBuilder->andWhere($queryBuilder->expr()->lte('up.updatedAt', ':endDate'))
                ->setParameter(':endDate', $getPointDto->endDate);
        }

        return $queryBuilder->getQuery()
            ->getSingleScalarResult();
    }

    public function getGroupedPoints(int $userId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        return $queryBuilder->select('IDENTITY(up.user) as user_id', 'IDENTITY(up.task) as task_id', 'SUM(up.points) as points')
            ->from(UserPoint::class, 'up')
            ->andWhere($queryBuilder->expr()->eq('up.user', ':userId'))
            ->groupBy('up.user, up.task')
            ->setParameters([':userId' => $userId])
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function removeByTask(int $userId, int $taskId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        return $queryBuilder->delete(UserPoint::class, 'up')
            ->andWhere($queryBuilder->expr()->eq('up.user', ':userId'))
            ->andWhere($queryBuilder->expr()->eq('up.task', ':taskId'))
            ->setParameters([':userId' => $userId, ':taskId' => $taskId])
            ->getQuery()
            ->execute();
    }
}
