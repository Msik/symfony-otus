<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'task')]
#[ORM\Entity]
class Task
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 140, nullable: false)]
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
