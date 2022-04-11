<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Upload
{
    #[Route('/api/upload', name: 'gallery-upload', methods: ['PUT', 'POST'])]
    public function upload(Request $request): Response
    {
        if ($request->files->count()) {
            try {
                return new JsonResponse($request->files->all());
            } catch (\Exception $exception) {
                return new JsonResponse(['error' => $exception->getMessage()]);
            }

        }

        return new JsonResponse(['error' => 'No file was uploaded']);
    }
}
