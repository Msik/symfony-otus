<?php

namespace App\Manager;

use App\Entity\Lesson;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getAllTasksForChoice(): array
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(Task::class);
        /** @var Task[] $tasks */
        $tasks = $repository->findAll();

        $result = [];
        foreach ($tasks as $task) {
            $result[$task->getTitle()] = $task->getId();
        }

        return $result;
    }

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

    public function storeTask(int $lessonId, string $titke): ?int
    {
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        /** @var Lesson $lesson */
        $lesson = $lessonRepository->find($lessonId);

        $task = new Task();
        $task->setLesson($lesson);
        $task->setTitle($titke);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task->getId();
    }

    public function updateTask(int $taskId, string $title): ?Task
    {
        $task = $this->getTaskById($taskId);
        if (!$task) {
            return null;
        }

        $task->setTitle($title);
        $this->entityManager->flush();

        return $task;
    }

    public function deleteTaskById(int $taskId): bool
    {
        $task = $this->getTaskById($taskId);
        if (!$task) {
            return false;
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }

    public function getTaskById(int $id): ?Task
    {
        /** @var TaskRepository $repository */
        $repository = $this->entityManager->getRepository(Task::class);

        return $repository->find($id);
    }
}
