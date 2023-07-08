<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserPointRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_point')]
#[ORM\Index(columns: ['user_id'], name: 'user_point__user_id__ind')]
#[ORM\Index(columns: ['task_id'], name: 'user_point__task_id__ind')]
#[ORM\Index(columns: ['skill_id'], name: 'user_point__skill_id__ind')]
#[ORM\Index(columns: ['user_id', 'task_id'], name: 'user_point__user_id__task_id__ind')]
#[ORM\Entity(repositoryClass: UserPointRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new Get(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class UserPoint
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'points')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    private Task $task;

    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    private ?Skill $skill = null;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $points = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): void
    {
        $this->skill = $skill;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'points' => $this->points,
        ];
    }
}
