<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\Hydration\Mapping\Composite\ConstrainedMapping;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\Specification\Contract\Satisfiable;

/** @deprecated */
final class Check
{
    /** @deprecated */
    public static function thatIt(
        Satisfiable $constraint,
        Mapping $mapping
    ): Mapping {
        return new ConstrainedMapping($constraint, $mapping);
    }
}
