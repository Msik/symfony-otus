<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'lesson')]
#[ORM\Index(columns: ['course_id'], name: 'lesson__course_id__ind')]
#[ORM\Index(columns: ['module_id'], name: 'lesson__module_id__ind')]
#[ORM\Entity]
class Lesson
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: false)]
    private Course $course;

    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(name: 'module_id', referencedColumnName: 'id')]
    private ?Module $module;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): void
    {
        $this->module = $module;
    }

    public function addTask(Task $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
    }
}
