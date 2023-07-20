<?php

namespace App\Service;

use App\Dto\ManageGetPointDto;
use App\Dto\ManageTaskPointDto;
use App\Entity\User;
use App\Manager\UserPointManager;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PointsCacheService
{
    private const CACHE_TAG = 'points';

    public function __construct(
        private readonly PointsService $pointsService,
        private readonly UserPointManager $userPointManager,
        private readonly TagAwareCacheInterface $cache,
    ) {}

    public function putByDto(User $user, ManageTaskPointDto $manageTaskPointDto): bool
    {
        $this->pointsService->putByDto($user, $manageTaskPointDto);

        $this->cache->invalidateTags([self::CACHE_TAG . $user->getId()]);

        return true;
    }

    public function getPointsByDto(int $userId, ManageGetPointDto $getPointDto): int
    {
        $pointsManager = $this->userPointManager;

        return $this->cache->get(
            sprintf('%s_%s_%s_%s_%s', $getPointDto->taskId, $getPointDto->skillId, $getPointDto->courseId, $getPointDto->startDate, $getPointDto->endDate),
            function (ItemInterface $item) use ($pointsManager, $userId, $getPointDto) {
                $points = $pointsManager->getPointsByDto($userId, $getPointDto);
                $item->set($points);
                $item->tag(self::CACHE_TAG . $userId);

                return $points;
            }
        );
    }
}
