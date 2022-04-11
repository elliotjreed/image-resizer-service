<?php

declare(strict_types=1);

namespace App\Service;

use Imagick;

final class Image
{
    public function __construct()
    {
    }

    public function get(string $fileName, string $dimensions): string
    {
        list($width, $height) = \explode('x', \strtolower($dimensions));

        $baseImageFilePath = \realpath(__DIR__ . '/../../public/images/' . $fileName);

        $this->validate($baseImageFilePath, $width, $height, $fileName);

        $resizedImageDirectory = __DIR__ . '/../../public/images/' . \strtolower($dimensions);

        $resizedFileName = $resizedImageDirectory . '/' . $fileName;

        if (\file_exists($resizedFileName)) {
            $file = new \SplFileObject($resizedFileName);

            return $file->fread($file->getSize());
        }

        if (!\is_dir($resizedImageDirectory)) {
            \mkdir($resizedImageDirectory, 0777);
        }

        $imagick = new Imagick($baseImageFilePath);
        $imagick->scaleImage((int) $width, (int) $height);
        $imagick->writeImage($resizedFileName);

        return $imagick->getImageBlob();
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

    private function dimensionsAreNumeric(string $width, string $height): bool
    {
        return \is_numeric($width) && \is_numeric($height);
    }

    private function validate(bool|string $baseImageFilePath, string $width, string $height, string $fileName): void
    {
        if (!$baseImageFilePath) {
            throw new \Exception('Base image does not exist');
        }

        if ($this->dimensionsAreNumeric($width, $height)) {
            throw new \Exception('Invalid dimensions format, must be in the format 123x456');
        }

        if (!$this->isAcceptableExtension($fileName)) {
            throw new \Exception('Requested image must be a JPEG or a PNG');
        }

        if ($width > 5000 || $height > 5000) {
            throw new \Exception('The maximum allowed dimensions are 5000x5000');
        }
    }
}
