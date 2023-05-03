<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ManageAuthCodeDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 16)]
        public string $phone = '',

        #[Assert\NotBlank]
        #[Assert\Length(max: 6)]
        public string $code = '',
    ) {}

    public static function fromRequest(Request $request): self
    {
        $body = json_decode($request->getContent(), true);

        return new self(
            phone: $request->request->get('phone') ?? $body['phone'] ?? '',
            code: $request->request->get('code') ?? $body['code'] ?? '',
        );
    }
}
