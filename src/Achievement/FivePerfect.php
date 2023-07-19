<?php

namespace App\Achievement;

use App\Entity\Achievement;
use App\Entity\User;
use App\Manager\UserPointManager;
use Doctrine\ORM\EntityManagerInterface;

class FivePerfect implements AchievementInterface
{
    const TITLE = 'five_perfect';

    public function __construct(
        private readonly UserPointManager $userPointManager,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function check(User $user): ?Achievement
    {
        $achvRepository = $this->entityManager->getRepository(Achievement::class);
        $achv = $achvRepository->findOneBy(['title' => self::TITLE]);

        if ($user->getAchievements()->contains($achv)) {
            return null;
        }

        $points = $this->userPointManager->getGroupedPointsByUser($user->getId());
        if (count($points) < 5) {
            return null;
        }

        foreach ($points as $point) {
            if ($point['points'] < 10) {
                return null;
            }
        }

        return $achv;
    }
}
