<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Dynamic;

use Closure;
use Stratadox\Hydration\Mapping\ClosureMapping;
use Stratadox\HydrationMapping\Mapping;

/** @deprecated */
final class ClosureResult
{
    /** @deprecated */
    public static function inProperty(string $name, Closure $function): Mapping
    {
        return ClosureMapping::inProperty($name, $function);
    }
}
