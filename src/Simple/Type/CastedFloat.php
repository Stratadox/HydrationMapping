<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Transform\KeyTransform;
use Stratadox\Hydration\Mapping\Primitive\FloatMapping;
use Stratadox\HydrationMapping\Mapping;

final class CastedFloat
{
    public static function inProperty(string $name): Mapping
    {
        return FloatMapping::inProperty($name);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return KeyTransform::use($key, FloatMapping::inProperty($name));
    }
}
