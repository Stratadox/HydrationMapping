<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\HydrationMapping\Mapping;

final class CanBeInteger
{
    public static function or(Mapping $mapping): Mapping
    {
        return CompositeMapping::either(
            IntegerValue::inProperty($mapping->name()),
            $mapping
        );
    }
}
