<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ManageTaskPointDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $taskId,
        #[Assert\LessThanOrEqual(10)]
        public int $points,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $body = json_decode($request->getContent(), true);

        return new self(
            taskId: $body['taskId'],
            points: $body['points'] ?? 0,
        );
    }
}
