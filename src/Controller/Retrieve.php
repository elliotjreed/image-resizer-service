<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\JpegResponse;
use App\Service\Image;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Retrieve
{
    #[Route(
        path: '/images/{dimensions}/{fileName}',
        name: 'retrieve',
        methods: ['GET', 'OPTIONS'],
        priority: -1
    )]
    public function get(Request $request, Image $image, string $fileName, string $dimensions): Response
    {
        $bestFit = $request->request->get('bestFit', false) === false;

        try {
            return new JpegResponse($image->get($fileName, $dimensions, $bestFit));
        } catch (Exception $exception) {
            return new Response($exception->getMessage());
        }
    }
}
