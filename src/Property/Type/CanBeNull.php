<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\Primitive\NullCheck;
use Stratadox\Hydration\Mapping\Primitive\NullMapping;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;

final class CanBeNull
{
    public static function or(KeyedMapping $mapping): KeyedMapping
    {
        return Keyed::mapping($mapping->key(),
            CompositeMapping::either(
                ConditionalMapping::ensureThat(NullCheck::passes(), NullMapping::inProperty($mapping->name())),
                $mapping
            )
        );
    }
}
