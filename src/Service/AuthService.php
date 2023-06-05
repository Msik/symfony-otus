<?php

namespace App\Service;

use App\Manager\UserManager;

class AuthService
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly AuthCodeService $authCodeService,
    ) {}

    public function isCredentialsValid(string $phone, string $code): bool
    {
        $user = $this->userManager->getUserByPhone($phone);
        if ($user === null) {
            return false;
        }

        $realCode = $this->authCodeService->getCode($phone);
        if ($realCode === null) {
            return false;
        }

        return $realCode === $code;
    }

    public function getToken(string $login): ?string
    {
        return $this->userManager->updateUserToken($login);
    }
}
