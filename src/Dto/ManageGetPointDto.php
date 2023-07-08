<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ManageGetPointDto
{
    public function __construct(
        public ?int $taskId = null,
        public ?int $skillId = null,
        public ?int $courseId = null,
        #[Assert\DateTime]
        public ?string $startDate = null,
        #[Assert\DateTime]
        public ?string $endDate = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $body = json_decode($request->getContent(), true);

        return new self(
            taskId: $request->query->get('task') ?? $body['task'] ?? null,
            skillId: $request->query->get('skill') ?? $body['skill'] ?? null,
            courseId: $request->query->get('course') ?? $body['course'] ?? null,
            startDate: $request->query->get('startDate') ?? $body['startDate'] ?? null,
            endDate: $request->query->get('endDate') ?? $body['endDate'] ?? null,
        );
    }
}
