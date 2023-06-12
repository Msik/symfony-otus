<?php

namespace UnitTests\Fixtures;

use App\Entity\Lesson;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MultipleTasksFixture extends Fixture
{
    public const TASK = 'Task #1';

    public function load(ObjectManager $manager): void
    {
        /** @var Lesson $lesson */
        $lesson = $this->getReference(MultipleLessonsFixture::COURSE_1_LESSON_1);

        $this->addReference(self::TASK, $this->makeTask($manager, $lesson, self::TASK));

        $manager->flush();
    }

    private function makeTask(ObjectManager $manager, Lesson $lesson, string $title): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setLesson($lesson);
        $manager->persist($task);

        return $task;
    }
}
