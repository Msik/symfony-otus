<?php

namespace App\Manager;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;

class CourseManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getCoursesByUser(int $userId, int $page, int $perPage): array
    {
        /** @var CourseRepository $repository */
        $repository = $this->entityManager->getRepository(Course::class);
        $paginator = $repository->getCoursesByUser($userId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'courses' => array_map(static fn ($course) => $course->toArray(), (array)$paginator->getIterator()),
        ];
    }

    public function storeCourse(string $title): ?int
    {
        $course = new Course;
        $course->setTitle($title);
        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return $course->getId();
    }
}
