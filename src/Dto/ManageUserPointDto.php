<?php

namespace App\Dto;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\UserPoint;
use Symfony\Component\Validator\Constraints as Assert;

class ManageUserPointDto
{
    public function __construct(
        public int $user = 0,
        public int $task = 0,
        #[Assert\LessThanOrEqual(10)]
        public int $points = 0,
    ) {}

    public static function fromEntity(UserPoint $userPoint): self
    {
        return new self(...[
            'user' => $userPoint->getUser()->getId(),
            'task' => $userPoint->getTask()->getId(),
            'points' => $userPoint->getPoints(),
        ]);
    }
}
