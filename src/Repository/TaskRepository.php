<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TaskRepository extends EntityRepository
{
    public function getTasks(int $lessonId, int $page, int $perPage): Paginator
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(Task::class, 't')
            ->andWhere($queryBuilder->expr()->eq('t.lesson', ':lesson'))
            ->setParameter(':lesson', $lessonId)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return new Paginator($queryBuilder);
    }
}
