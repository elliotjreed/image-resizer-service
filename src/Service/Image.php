<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\DimensionLimitExceeded;
use App\Exception\InvalidDimensions;
use App\Exception\UnsupportedFileType;
use Imagick;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

final class Image
{
    public function get(string $fileName, string $dimensions, bool $bestFit = false): string
    {
        $this->validateDimensions($dimensions);

        $baseImageFilePath = __DIR__ . '/../../public/images/' . $fileName;
        try {
            $baseImageFile = new File($baseImageFilePath, checkPath: true);
        } catch (FileNotFoundException) {
            throw new \Exception($baseImageFilePath);
        }

        $extension = $baseImageFile->guessExtension();
        if (!\in_array($extension, ['jpg', 'jpeg', 'png'])) {
            throw new UnsupportedFileType($extension);
        }

        $resizedImageDirectory = __DIR__ . '/../../public/images/' . \strtolower($dimensions);

        $resizedFileName = $resizedImageDirectory . '/' . $fileName;
        $resizedFile = new File($resizedImageDirectory . '/' . $fileName, checkPath: false);

        if ($resizedFile->isFile()) {
            return $resizedFile->getContent();
        }

        if (!\is_dir($resizedImageDirectory)) {
            \mkdir($resizedImageDirectory, 0775);
        }

        $imagick = new Imagick($baseImageFile->getRealPath());

        list($width, $height) = \explode('x', \strtolower($dimensions));
        $imagick->scaleImage((int) $width, (int) $height, $bestFit);
        $imagick->writeImage($resizedFileName);

        \chmod(\realpath($resizedFileName), 0644);

        return $imagick->getImageBlob();
    }

    private function validateDimensions(string $dimensions): void
    {
        list($width, $height) = \explode('x', \strtolower($dimensions));

        if (!$this->dimensionsAreNumeric($width, $height)) {
            throw new InvalidDimensions($dimensions);
        }

        if ($width > 5000 || $height > 5000) {
            throw new DimensionLimitExceeded($dimensions);
        }

        if ($width < 1 || $height < 1) {
            throw new InvalidDimensions($dimensions);
        }
    }

    private function dimensionsAreNumeric(mixed $width, mixed $height): bool
    {
        return \is_numeric($width) && \is_numeric($height);
    }
}
