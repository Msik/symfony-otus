<?php

namespace App\Service;

use App\Manager\UserManager;

class AuthService
{
    public function __construct(
        private readonly UserManager $userManager
    ) {}

    public function isCredentialsValid(string $phone, string $code): bool
    {
        $user = $this->userManager->getUserByPhone($phone);
        if ($user === null) {
            return false;
        }

        // TODO добавить проверку кода
        return true;
    }

    public function getToken(string $login): ?string
    {
        return $this->userManager->updateUserToken($login);
    }
}
