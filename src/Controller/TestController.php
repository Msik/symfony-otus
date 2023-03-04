<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>' . $number . '</body></html>'
        );
    }
}
