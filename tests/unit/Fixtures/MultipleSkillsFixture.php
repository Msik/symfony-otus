<?php

namespace UnitTests\Fixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MultipleSkillsFixture extends Fixture
{
    public const SKILL_1 = 'Skill #1';
    public const SKILL_2 = 'SKill #1';

    public function load(ObjectManager $manager): void
    {
        $this->addReference(self::SKILL_1, $this->makeSkill($manager, self::SKILL_1));
        $this->addReference(self::SKILL_2, $this->makeSkill($manager, self::SKILL_2));

        $manager->flush();
    }

    private function makeSkill(ObjectManager $manager, string $title): Skill
    {
        $skill = new Skill();
        $skill->setTitle($title);
        $manager->persist($skill);

        return $skill;
    }
}
