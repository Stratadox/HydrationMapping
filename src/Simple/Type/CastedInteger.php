<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Transform\KeyTransform;
use Stratadox\Hydration\Mapping\Primitive\IntegerMapping;
use Stratadox\Hydration\Mapping\Simple\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\Mapping;

final class CastedInteger
{
    public static function inProperty(string $name): Mapping
    {
        return IntegerMapping::inProperty($name);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return KeyTransform::use($key, IntegerMapping::inProperty($name));
    }
}
