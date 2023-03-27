<?php

namespace App\Entity\Trait;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableTrait
{
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    #[Gedmo\Timestampable(on: 'update')]
    private DateTime $updatedAt;

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
