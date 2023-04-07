<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class LessonRepository extends EntityRepository
{
    public function getLessonsByCourse(int $courseId, int $page, int $perPage): Paginator
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('t')
            ->from(Lesson::class, 't')
            ->andWhere($queryBuilder->expr()->eq('t.course', ':course'))
            ->setParameter(':course', $courseId)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return new Paginator($queryBuilder);
    }
}
