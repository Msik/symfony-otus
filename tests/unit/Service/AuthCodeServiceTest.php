<?php

namespace UnitTests\Service;

use App\Entity\User;
use App\Manager\UserManager;
use App\Service\AuthCodeService;
use App\Service\AuthService;
use PHPUnit\Framework\TestCase;

class AuthCodeServiceTest extends TestCase
{
    const TEST_PHONE = '79991112233';
    const TEST_CODE = '123123';
    const TEST_WRONG_CODE = '321321';

    public function testCheckingCredentialsNonExistingUser(): void
    {
        $userManager = $this->createMock(UserManager::class);
        $userManager->method('getUserByPhone')
            ->willReturn(null);

        $authCodeService = $this->createMock(AuthCodeService::class);
        $authCodeService->method('getCode')
            ->willReturn(self::TEST_CODE);

        $authService = new AuthService($userManager, $authCodeService);

        $result = $authService->isCredentialsValid(self::TEST_PHONE, self::TEST_CODE);

        static::assertFalse($result);
    }

    public function testCheckingCredentialsWrongCode(): void
    {
        $userManager = $this->createMock(UserManager::class);
        $userManager->method('getUserByPhone')
            ->willReturn(new User());

        $authCodeService = $this->createMock(AuthCodeService::class);
        $authCodeService->method('getCode')
            ->willReturn(self::TEST_WRONG_CODE);

        $authService = new AuthService($userManager, $authCodeService);

        $result = $authService->isCredentialsValid(self::TEST_PHONE, self::TEST_CODE);

        static::assertFalse($result);
    }

    public function testCheckingCredentialsSuccess(): void
    {
        $userManager = $this->createMock(UserManager::class);
        $userManager->method('getUserByPhone')
            ->willReturn(new User());

        $authCodeService = $this->createMock(AuthCodeService::class);
        $authCodeService->method('getCode')
            ->willReturn(self::TEST_CODE);

        $authService = new AuthService($userManager, $authCodeService);

        $result = $authService->isCredentialsValid(self::TEST_PHONE, self::TEST_CODE);

        static::assertTrue($result);
    }
}
