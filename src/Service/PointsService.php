<?php

namespace App\Service;

use App\Dto\ManageTaskPointDto;
use App\Entity\User;
use App\Entity\TaskSkillProportion;
use App\Entity\UserPoint;
use App\Manager\TaskManager;
use App\Manager\UserPointManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PointsService
{
    private const CACHE_TAG = 'points';

    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly UserPointManager $userPointManager,
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache,
    ) {}

    public function putByDto(User $user, ManageTaskPointDto $manageTaskPointDto): bool
    {
        $task = $this->taskManager->getTaskById($manageTaskPointDto->taskId);

        $proportionPoints = $this->getPointsByProportion($task->getSkillProportion(), $manageTaskPointDto->points);
        foreach ($proportionPoints as $points) {
            $userPoint = new UserPoint();
            $userPoint->setUser($user);
            $userPoint->setTask($task);
            $userPoint->setSkill($points['skill']);
            $userPoint->setPoints($points['points']);
            $this->entityManager->persist($userPoint);
        }

        $this->entityManager->flush();
        $this->cache->invalidateTags([self::CACHE_TAG . $user->getId()]);

        return true;
    }

    public function getPointsByProportion(Collection $proportions, int $points): array
    {
        $proportionPoints = [];
        $remains = $points;
        /** @var TaskSkillProportion $proportion */
        foreach ($proportions as $proportion) {
            $tmpPoints = round($points * $proportion->getProportion() / 100);
            $proportionPoints[] = [
                'skill' => $proportion->getSkill(),
                'points' => $tmpPoints,
            ];
            $remains -= $tmpPoints;
        }

        if ($remains > 0) {
            $proportionPoints[] = [
                'skill' => null,
                'points' => $remains,
            ];
        }

        return $proportionPoints;
    }

    public function getPoints(int $userId, ?int $taskId = null, ?int $skillId = null): int
    {
        $userPointManager = $this->userPointManager;

        return $this->cache->get(
            "points_{$userId}_{$taskId}_{$skillId}",
            function(ItemInterface $item) use ($userPointManager, $userId, $taskId, $skillId) {
                $points = $userPointManager->getPoints($userId, $taskId, $skillId);
                $item->set($points);
                $item->tag(self::CACHE_TAG . $userId);

                return $points;
            }
        );
    }
}
