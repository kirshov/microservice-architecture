<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController
{
    #[Route('/health', name: 'healthcheck')]
    public function healthcheck(): Response
    {
        $response = ['status' => 'OK'];

        return new JsonResponse($response);
    }
}
