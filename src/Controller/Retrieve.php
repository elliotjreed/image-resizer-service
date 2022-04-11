<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\JpegResponse;
use Imagick;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class Retrieve
{
    #[Route(
        path: '/images/{dimensions}/{fileName}',
        name: 'retrieve',
        methods: ['GET', 'OPTIONS'],
        priority: -1
    )]
    public function get(string $fileName, string $dimensions): Response
    {
        list($width, $height) = \explode('x', \strtolower($dimensions));

        $baseImageFilePath = \realpath(__DIR__ . '/../../public/images/' . $fileName);

        if (!\is_numeric($width) || !\is_numeric($height) || !$this->isAcceptableExtension($fileName) || !\file_exists($baseImageFilePath)) {
            throw new NotFoundHttpException();
        }

        if ($width > 5000 || $height > 5000) {
            return new JsonResponse('No no, not too big');
        }

        $resizedImageDirectory = __DIR__ . '/../../public/images/' . \strtolower($dimensions);

        $resizedFileName = $resizedImageDirectory . '/' . $fileName;

        if (\file_exists($resizedFileName)) {
            $file = new \SplFileObject($resizedFileName);

            return new JpegResponse($file->fread($file->getSize()));
        }

        if (!\is_dir($resizedImageDirectory)) {
            \mkdir($resizedImageDirectory, 0777);
        }

        $imagick = new Imagick($baseImageFilePath);
        $imagick->scaleImage((int) $width, (int) $height);
        $imagick->writeImage($resizedFileName);

        return new JpegResponse($imagick->getImageBlob());
    }

    private function isAcceptableExtension(string $fileName): bool
    {
        $extensions = ['.jpg', '.jpeg', '.png'];
        foreach ($extensions as $extension) {
            if (\str_ends_with($fileName, $extension)) {
                return true;
            }
        }

        return false;
    }
}
