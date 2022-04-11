<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class UnsupportedFileType extends Exception
{
    protected $message = 'Unsupported file type';
}
