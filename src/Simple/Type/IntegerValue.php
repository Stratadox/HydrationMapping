<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Primitive\IntegerCheck;
use Stratadox\Hydration\Mapping\Primitive\IntegerMapping;
use Stratadox\HydrationMapping\Mapping;

final class IntegerValue
{
    public static function inProperty(string $name): Mapping
    {
        return ConditionalMapping::ensureThat(
            IntegerCheck::passes(),
            IntegerMapping::inProperty($name)
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return DifferentKey::use($key, self::inProperty($name));
    }
}
