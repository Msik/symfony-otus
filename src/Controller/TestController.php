<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function number(): Response
    {
        return new JsonResponse([
            'hello' => 'world'
        ]);
    }
}
