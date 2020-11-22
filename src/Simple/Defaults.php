<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\FixedMapping;
use Stratadox\HydrationMapping\Mapping;

final class Defaults
{
    /**
     * @param mixed $defaultValue
     * @param Mapping $mapping
     * @return Mapping
     */
    public static function to(
        $defaultValue,
        Mapping $mapping
    ): Mapping {
        return CompositeMapping::either(
            $mapping,
            FixedMapping::inProperty($mapping->name(), $defaultValue)
        );
    }
}
