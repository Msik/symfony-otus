<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'course')]
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Lesson::class)]
    private Collection $lessons;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Module::class)]
    private Collection $modules;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'courses')]
    #[ORM\JoinTable(name: 'user_course')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $users;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->modules = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function addLesson(Lesson $lesson)
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
    }

    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addModule(Module $module)
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
        }
    }

    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'lessons' => [],
            'modules' => [],
            'users' => [],
        ];
    }
}
