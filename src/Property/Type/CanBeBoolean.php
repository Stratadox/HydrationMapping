<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\Primitive\BooleanMapping;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;

final class CanBeBoolean
{
    public static function or(KeyedMapping $mapping): KeyedMapping
    {
        return Keyed::mapping($mapping->key(),
            CompositeMapping::either(
                BooleanMapping::inProperty($mapping->key()),
                $mapping
            )
        );
    }

    public static function orCustom(
        KeyedMapping $mapping,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        return Keyed::mapping($mapping->key(),
            CompositeMapping::either(
                BooleanMapping::custom($mapping->key(), $truths, $falsehoods),
                $mapping
            )
        );
    }
}
