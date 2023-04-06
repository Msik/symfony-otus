<?php

namespace App\Manager;

use App\Entity\Course;
use App\Entity\Module;
use App\Repository\ModuleRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'lessons/{lessonId}/tasks')]
class ModuleManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getModulesByCourse(int $courseId, int $page, int $perPage): array
    {
        /** @var ModuleRepository $repository */
        $repository = $this->entityManager->getRepository(Module::class);
        $paginator = $repository->getModulesByCourse($courseId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'modules' => array_map(static fn (Module $module) => $module->toArray(), (array)$paginator->getIterator()),
        ];
    }

    public function storeModule(int $courseId, string $titke): ?int
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);
        /** @var Course $course */
        $course = $courseRepository->find($courseId);

        $module = new Module();
        $module->setCourse($course);
        $module->setTitle($titke);
        $this->entityManager->persist($module);
        $this->entityManager->flush();

        return $module->getId();
    }

    public function updateModule(int $moduleId, string $title): ?Module
    {
        /** @var ModuleRepository $repository */
        $repository = $this->entityManager->getRepository(Module::class);
        /** @var Module $module */
        $module = $repository->find($moduleId);
        if (!$module) {
            return null;
        }

        $module->setTitle($title);
        $this->entityManager->flush();

        return $module;
    }

    public function deleteModuleById(int $moduleId): bool
    {
        /** @var ModuleRepository $repository */
        $repository = $this->entityManager->getRepository(Module::class);
        /** @var Module $module */
        $module = $repository->find($moduleId);
        if (!$module) {
            return false;
        }

        $this->entityManager->remove($module);
        $this->entityManager->flush();

        return true;
    }
}
