<?php

namespace App\Controller\Api\v1;

use App\Dto\ManageAuthCodeDto;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/v1/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route(path: '', methods: ['POST'])]
    public function getTokenAction(Request $request): Response
    {
        $authCodeDto = ManageAuthCodeDto::fromRequest($request);
        $errors = $this->validator->validate($authCodeDto);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => 'Wrong request'], Response::HTTP_FORBIDDEN);
        }

        if (!$this->authService->isCredentialsValid($authCodeDto->phone, $authCodeDto->code)) {
            return new JsonResponse(['message' => 'Invalid code'], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['token' => $this->authService->getToken($authCodeDto->phone)]);
    }
}
