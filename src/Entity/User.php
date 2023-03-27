<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`user`')]
#[ORM\Entity]
class User
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 16, nullable: false)]
    private string $phone;

    #[ORM\OneToMany(targetEntity: UserPoint::class, mappedBy: 'user')]
    private Collection $points;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'users')]
    private Collection $courses;

    public function __construct()
    {
        $this->points = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function addPoint(UserPoint $userPoint): void
    {
        if (!$this->points->contains($userPoint)) {
            $this->points->add($userPoint);
        }
    }

    public function addCourse(Course $course): void
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }
}
