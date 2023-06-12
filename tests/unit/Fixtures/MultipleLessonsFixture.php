<?php

namespace UnitTests\Fixtures;

use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MultipleLessonsFixture extends Fixture
{
    public const COURSE_1_LESSON_1 = 'Course #1, Lesson #1';
    public const COURSE_1_LESSON_2 = 'Course #1, Lesson #2';

    public const COURSE_2_LESSON_1 = 'Course #2, Lesson #1';
    public const COURSE_2_LESSON_2 = 'Course #2, Lesson #2';

    public function load(ObjectManager $manager): void
    {
        /** @var Course $course1 */
        $course1 = $this->getReference(MultipleCoursesFixture::COURSE_1);
        /** @var Course $course2 */
        $course2 = $this->getReference(MultipleCoursesFixture::COURSE_2);

        $this->addReference(self::COURSE_1_LESSON_1, $this->makeLesson($manager, $course1, self::COURSE_1_LESSON_1));
        $this->addReference(self::COURSE_1_LESSON_2, $this->makeLesson($manager, $course1, self::COURSE_1_LESSON_2));

        $this->addReference(self::COURSE_2_LESSON_1, $this->makeLesson($manager, $course2, self::COURSE_2_LESSON_1));
        $this->addReference(self::COURSE_2_LESSON_2, $this->makeLesson($manager, $course2, self::COURSE_2_LESSON_2));

        $manager->flush();
    }

    private function makeLesson(ObjectManager $manager, Course $course, string $title): Lesson
    {
        $lesson = new Lesson();
        $lesson->setTitle($title);
        $lesson->setCourse($course);
        $manager->persist($lesson);

        return $lesson;
    }
}
