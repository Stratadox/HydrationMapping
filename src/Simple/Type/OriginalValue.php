<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\Transform\KeyTransform;
use Stratadox\Hydration\Mapping\Primitive\OriginalMapping;
use Stratadox\HydrationMapping\Mapping;

final class OriginalValue
{
    public static function inProperty(string $name): Mapping
    {
        return OriginalMapping::inProperty($name);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return KeyTransform::use($key, OriginalMapping::inProperty($name));
    }
}
