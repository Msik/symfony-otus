<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'task')]
#[ORM\Index(columns: ['lesson_id'], name: 'task__lesson_id__ind')]
#[ORM\Entity]
class Task
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Lesson::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'lesson_id', referencedColumnName: 'id', nullable: false)]
    private Lesson $lesson;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
