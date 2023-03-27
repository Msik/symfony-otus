<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'module')]
#[ORM\Index(columns: ['course_id'], name: 'module__course_id__ind')]
#[ORM\Entity]
class Module
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'modules')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: false)]
    private Course $course;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Lesson::class)]
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function addLesson(Lesson $lesson)
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
    }
}
