<?php

namespace App\Entity;

use App\Entity\Trait\IdentiableTrait;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'course')]
#[ORM\Entity]
class Course
{
    use IdentiableTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Lesson::class)]
    private Collection $lessons;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Module::class)]
    private Collection $modules;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'courses')]
    #[ORM\JoinTable(name: 'user_course')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'course_id', referencedColumnName: 'id')]
    private Collection $users;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->modules = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function addLesson(Lesson $lesson)
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
    }

    public function addModule(Module $module)
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
        }
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }
}
