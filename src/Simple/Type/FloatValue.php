<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Primitive\FloatCheck;
use Stratadox\Hydration\Mapping\Primitive\FloatMapping;
use Stratadox\Hydration\Mapping\Simple\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\Mapping;

final class FloatValue
{
    public static function inProperty(string $name): Mapping
    {
        return ConditionalMapping::ensureThat(
            FloatCheck::passes(),
            FloatMapping::inProperty($name)
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return DifferentKey::use($key, self::inProperty($name));
    }
}
