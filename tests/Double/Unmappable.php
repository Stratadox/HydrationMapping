<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use RuntimeException;
use Stratadox\Hydration\UnmappableInput;

class Unmappable extends RuntimeException implements UnmappableInput
{
}
