<?php

declare(strict_types=1);

namespace Kreait\Firebase\Exception\Database;

use Kreait\Firebase\Exception\DatabaseException;
use RuntimeException;

final class PermissionDenied extends RuntimeException implements DatabaseException
{
}
