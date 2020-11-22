<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\Primitive\NullCheck;
use Stratadox\Hydration\Mapping\Primitive\NullMapping;
use Stratadox\HydrationMapping\Mapping;

final class CanBeNull
{
    public static function or(Mapping $mapping): Mapping
    {
        return CompositeMapping::either(
            ConditionalMapping::ensureThat(
                NullCheck::passes(),
                NullMapping::inProperty($mapping->name())
            ),
            $mapping
        );
    }
}
