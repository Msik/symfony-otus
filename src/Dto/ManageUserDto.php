<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ManageUserDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 16)]
        public string $phone = '',

        #[Assert\Type('array')]
        public array $roles = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        $body = json_decode($request->getContent(), true);
        /** @var array $roles */
        $roles = $request->request->get('roles') ?? $body['roles'] ?? $request->query->get('roles') ?? [];;

        return new self(
            phone: $request->request->get('phone') ?? $body['phone'] ?? $request->query->get('phone'),
            roles: $roles,
        );
    }
}
