<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\JpegResponse;
use App\Service\Image;
use Exception;
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
    public function get(Image $image, string $fileName, string $dimensions): Response
    {
        try {
            return new JpegResponse($image->get($fileName, $dimensions));
        } catch (Exception $exception) {
            return new Response($exception->getMessage());
        }
    }
}
