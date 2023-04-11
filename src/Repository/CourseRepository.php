<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CourseRepository extends EntityRepository
{
    public function getCoursesByUser(int $userId, int $page, int $perPage): Paginator
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(Course::class, 't')
            ->andWhere($queryBuilder->expr()->isMemberOf(':user', 't.users'))
            ->setParameter(':user', $userId)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return new Paginator($queryBuilder);
    }
}
