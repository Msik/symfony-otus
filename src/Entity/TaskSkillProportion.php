<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserPointRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'task_skill_proportion')]
#[ORM\Index(columns: ['task_id'], name: 'task_skill_proportion__task_id__ind')]
#[ORM\Index(columns: ['skill_id'], name: 'task_skill_proportion__skill_id__ind')]
#[ORM\Entity(repositoryClass: UserPointRepository::class)]
class TaskSkillProportion
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    private Task $task;

    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    private Skill $skill;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $proportion = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): void
    {
        $this->skill = $skill;
    }

    public function getProportion(): int
    {
        return $this->proportion;
    }

    public function setProportion(int $proportion): void
    {
        $this->proportion = $proportion;
    }
}
