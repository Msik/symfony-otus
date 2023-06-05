<?php

namespace App\Service;

use App\Dto\ManageTaskPointDto;
use App\Entity\User;
use App\Entity\TaskSkillProportion;
use App\Entity\UserPoint;
use App\Manager\TaskManager;
use App\Repository\UserPointRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class PointsService
{
    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function putByDto(User $user, ManageTaskPointDto $manageTaskPointDto): bool
    {
        $task = $this->taskManager->getTaskById($manageTaskPointDto->taskId);
        if (!$task) {
            throw new InvalidArgumentException('Wrong task');
        }

        // TODO sql transaction
        /** @var UserPointRepository $repository */
        $userPointRepository = $this->entityManager->getRepository(UserPoint::class);
        $userPointRepository->removeByTask($user->getId(), $task->getId());

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
}
