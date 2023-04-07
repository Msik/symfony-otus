<?php

namespace App\Manager;

use App\Entity\Lesson;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;

class LessonManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getLessonsByCourse(int $courseId, int $page, int $perPage): array
    {
        /** @var LessonRepository $repository */
        $repository = $this->entityManager->getRepository(Lesson::class);
        $paginator = $repository->getLessonsByCourse($courseId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'lessons' => array_map(static fn (Lesson $lesson) => $lesson->toArray(), (array)$paginator->getIterator()),
        ];
    }
}
