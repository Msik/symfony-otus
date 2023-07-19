<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new Get(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 16, nullable: false, unique: true)]
    private string $phone;

    #[ORM\OneToMany(targetEntity: UserPoint::class, mappedBy: 'user')]
    private Collection $points;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'users')]
    private Collection $courses;

    #[ORM\ManyToMany(targetEntity: Achievement::class, mappedBy: 'users')]
    private Collection $achievements;

    #[ORM\Column(type: 'json', length: 1024, nullable: false, options: ['default' => '[]'])]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: true)]
    private ?string $token = null;

    public function __construct()
    {
        $this->points = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->achievements = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function addAchievement(Achievement $achievement): void
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements->add($achievement);
        }
    }

    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->phone;
    }

    public function getUserIdentifier(): string
    {
        return $this->phone;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'roles' => $this->getRoles(),
            'courses' => array_map(static fn (Course $course) => $course->toArray(), $this->courses->toArray()),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
