<?php

namespace App\Manager;

use App\Dto\ManageUserPointDto;
use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\UserPoint;
use App\Repository\UserPointRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserPointManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getPointsByUser(int $userId, int $page, int $perPage): array
    {
        /** @var UserPointRepository $repository */
        $repository = $this->entityManager->getRepository(UserPoint::class);
        $paginator = $repository->getPointsByUser($userId, $page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'points' => array_map(static fn (UserPoint $userPoint) => $userPoint->toArray(), (array)$paginator->getIterator()),
        ];
    }

    public function storeUserPoint(
        int $userId,
        int $taskId,
        int $points,
        ?int $skillId = null
    ): ?int {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($userId);

        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->entityManager->getRepository(Task::class);
        /** @var Task $task */
        $task = $taskRepository->find($taskId);

        $userPoint = new UserPoint();
        $userPoint->setUser($user);
        $userPoint->setTask($task);
        $userPoint->setPoints($points);

        if ($skillId) {
            /** @var SkillRepository $skillRepository */
            $skillRepository = $this->entityManager->getRepository(Skill::class);
            /** @var Skill $module */
            $skill = $skillRepository->find($skillId);
            $userPoint->setSkill($skill);
        }

        $this->entityManager->persist($userPoint);
        $this->entityManager->flush();

        return $userPoint->getId();
    }

    public function updateUserPoint(int $userPointId, int $points): ?UserPoint
    {
        $userPoint = $this->getUserPointById($userPointId);
        if (!$userPoint) {
            return null;
        }

        $userPoint->setPoints($points);
        $this->entityManager->flush();

        return $userPoint;
    }

    public function deleteUserPointById(int $userPointId): bool
    {
        $userPoint = $this->getUserPointById($userPointId);
        if (!$userPoint) {
            return false;
        }

        $this->entityManager->remove($userPoint);
        $this->entityManager->flush();

        return true;
    }

    public function savePointByDto(UserPoint $userPoint, ManageUserPointDto $manageUserPointDto): ?int
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($manageUserPointDto->user);

        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->entityManager->getRepository(Task::class);
        /** @var Task $task */
        $task = $taskRepository->find($manageUserPointDto->task);

        $userPoint->setUser($user);
        $userPoint->setTask($task);
        $userPoint->setPoints($manageUserPointDto->points);
        $this->entityManager->persist($userPoint);
        $this->entityManager->flush();

        return $userPoint->getId();
    }

    public function getUserPointById(int $id): ?UserPoint
    {
        /** @var UserPointRepository $repository */
        $repository = $this->entityManager->getRepository(UserPoint::class);

        return $repository->find($id);
    }
}
