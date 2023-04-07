<?php

namespace App\Manager;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Module;
use App\Repository\LessonRepository;
use App\Repository\CourseRepository;
use App\Repository\ModuleRepository;
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

    public function storeLesson(int $courseId, string $title, ?int $moduleId = null): ?int
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);
        /** @var Course $course */
        $course = $courseRepository->find($courseId);

        $lesson = new Lesson();
        $lesson->setCourse($course);
        $lesson->setTitle($title);

        if ($moduleId) {
            /** @var ModuleRepository $courseRepository */
            $moduleRepository = $this->entityManager->getRepository(Module::class);
            /** @var Module $module */
            $module = $moduleRepository->find($courseId);
            $lesson->setModule($module);
        }

        $this->entityManager->persist($lesson);
        $this->entityManager->flush();

        return $lesson->getId();
    }

    public function updateLesson(int $lessonId, string $title): ?Lesson
    {
        /** @var LessonRepository $repository */
        $repository = $this->entityManager->getRepository(Lesson::class);
        /** @var Lesson $lesson */
        $lesson = $repository->find($lessonId);
        if (!$lesson) {
            return null;
        }

        $lesson->setTitle($title);
        $this->entityManager->flush();

        return $lesson;
    }
}
