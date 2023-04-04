<?php

namespace App\Manager;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getTasks(int $lessonId, int $page, int $perPage): array
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(Task::class);
        $paginator = $repository->getTasks($lessonId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'tasks' => array_map(static fn (Task $task) => $task->toArray(), (array)$paginator->getIterator()),
        ];
    }
}
