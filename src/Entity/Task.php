<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'task')]
#[ORM\Index(columns: ['lesson_id'], name: 'task__lesson_id__ind')]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Lesson::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'lesson_id', referencedColumnName: 'id', nullable: false)]
    private Lesson $lesson;

    #[ORM\OneToMany(targetEntity: TaskSkillProportion::class, mappedBy: 'task')]
    private Collection $skillProportion;

    public function __construct()
    {
        $skillProportion = new ArrayCollection();
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

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $lesson): void
    {
        $this->lesson = $lesson;
    }

    public function addSkillProportion(TaskSkillProportion $taskSkillProportion): void
    {
        if (!$this->skillProportion->contains($taskSkillProportion)) {
            $this->skillProportion->add($taskSkillProportion);
        }
    }

    public function getSkillProportion(): Collection
    {
        return $this->skillProportion;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
