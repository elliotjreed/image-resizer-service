<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class DimensionLimitExceeded extends Exception
{
    protected $message = 'Dimension limit exceeded';
}
