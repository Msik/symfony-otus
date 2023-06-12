<?php

namespace UnitTests\Fixtures;

use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\TaskSkillProportion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use UnitTests\Fixtures\MultipleSkillsFixture;

class MultipleTaskSkillProportionsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var Task $task */
        $task = $this->getReference(MultipleTasksFixture::TASK);
        /** @var Skill $skill1 */
        $skill1 = $this->getReference(MultipleSkillsFixture::SKILL_1);
        /** @var Skill $skill2 */
        $skill2 = $this->getReference(MultipleSkillsFixture::SKILL_2);

        $this->makeProportion($manager, $task, $skill1, 80);
        $this->makeProportion($manager, $task, $skill2, 20);

        $manager->flush();
    }

    private function makeProportion(ObjectManager $manager, Task $task, Skill $skill, int $proprotion): TaskSkillProportion
    {
        $taskProprotion = new TaskSkillProportion();
        $taskProprotion->setTask($task);
        $taskProprotion->setSkill($skill);
        $taskProprotion->setProportion($proprotion);
        $manager->persist($taskProprotion);

        return $taskProprotion;
    }
}
