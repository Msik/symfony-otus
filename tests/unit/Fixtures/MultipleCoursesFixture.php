<?php

namespace UnitTests\Fixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MultipleCoursesFixture extends Fixture
{
    public const COURSE_1 = 'Course #1';
    public const COURSE_2 = 'Course #2';

    public function load(ObjectManager $manager): void
    {
        $this->addReference(self::COURSE_1, $this->makeCourse($manager, self::COURSE_1));
        $this->addReference(self::COURSE_2, $this->makeCourse($manager, self::COURSE_2));
        $manager->flush();
    }

    private function makeCourse(ObjectManager $manager, string $title): Course
    {
        $course = new Course();
        $course->setTitle($title);
        $manager->persist($course);

        return $course;
    }
}
