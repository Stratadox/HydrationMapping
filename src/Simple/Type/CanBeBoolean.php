<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\Primitive\BooleanMapping;
use Stratadox\HydrationMapping\Mapping;

final class CanBeBoolean
{
    public static function or(Mapping $mapping): Mapping
    {
        return CompositeMapping::either(
            BooleanMapping::inProperty($mapping->name()),
            $mapping
        );
    }

    public static function orCustom(
        Mapping $mapping,
        array $truths,
        array $falsehoods
    ): Mapping {
        return CompositeMapping::either(
            BooleanMapping::custom($mapping->name(), $truths, $falsehoods),
            $mapping
        );
    }
}
