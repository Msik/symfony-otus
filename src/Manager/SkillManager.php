<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class SkillManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getSkills(int $page, int $perPage): array
    {
        /** @var SkillRepository $repository */
        $repository = $this->entityManager->getRepository(Skill::class);
        $paginator = $repository->getSkills($page, $perPage);
        $maxPage = ceil($paginator->count() / $perPage);

        return [
            'maxPage' => $maxPage,
            'skills' => array_map(static fn (Skill $skill) => $skill->toArray(), (array)$paginator->getIterator()),
        ];
    }

    public function storeSkill(string $titke): ?int
    {
        $skill = new Skill();
        $skill->setTitle($titke);
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill->getId();
    }

    public function updateSkill(int $skillId, string $title): ?Skill
    {
        /** @var SkillRepository $repository */
        $repository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $repository->find($skillId);
        if (!$skill) {
            return null;
        }

        $skill->setTitle($title);
        $this->entityManager->flush();

        return $skill;
    }

    public function deleteSkillById(int $skillId): bool
    {
        /** @var SkillRepository $repository */
        $repository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $repository->find($skillId);
        if (!$skill) {
            return false;
        }

        $this->entityManager->remove($skill);
        $this->entityManager->flush();

        return true;
    }
}
