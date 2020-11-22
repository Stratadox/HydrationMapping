<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\CompositeMapping;
use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\Primitive\FloatCheck;
use Stratadox\Hydration\Mapping\Primitive\FloatMapping;
use Stratadox\HydrationMapping\Mapping;

final class CanBeFloat
{
    public static function or(Mapping $mapping): Mapping
    {
        return CompositeMapping::either(ConditionalMapping::ensureThat(
            FloatCheck::passes(),
            FloatMapping::inProperty($mapping->name())
        ), $mapping);
    }
}
