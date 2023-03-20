<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`user`')]
#[ORM\Entity]
class User
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $phone;

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
