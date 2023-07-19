<?php

namespace App\Service;

use App\Achievement\FivePerfect;
use App\Entity\User;
use App\Achievement\AchievementInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AchievementService
{
    private array $listAchievements = [
        FivePerfect::class,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ContainerInterface $container
    ) {}

    public function checkAchievements(User $user): void
    {
        foreach ($this->listAchievements as $checkerClass) {
            /** @var AchievementInterface $checker */
            $checker = $this->container->get($checkerClass);
            $achive = $checker->check($user);
            if ($achive !== null) {
                $user->addAchievement($achive);
                $achive->addUser($user);
            }
        }

        $this->entityManager->flush();
    }
}
