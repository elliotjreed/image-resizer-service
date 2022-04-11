<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class Root extends AbstractController
{
    #[Route('/', methods: ['GET', 'POST', 'PUT', 'PATCH'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
