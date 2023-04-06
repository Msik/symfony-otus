<?php

namespace App\Manager;

use App\Entity\Module;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;

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
}
