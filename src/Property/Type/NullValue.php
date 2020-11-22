<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Primitive\NullMapping;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;

final class NullValue
{
    public static function inProperty(string $name): KeyedMapping
    {
        return Keyed::mapping($name, NullMapping::inProperty($name));
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use($key,
            NullMapping::inProperty($name)
        ));
    }
}
