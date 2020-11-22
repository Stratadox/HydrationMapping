<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Type;

use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Simple\Keyed;
use Stratadox\Hydration\Mapping\Primitive\StringMapping;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\Mapping;

final class StringValue
{
    public static function inProperty(string $name): Mapping
    {
        return StringMapping::inProperty($name);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): Mapping {
        return DifferentKey::use($key, StringMapping::inProperty($name));
    }
}
