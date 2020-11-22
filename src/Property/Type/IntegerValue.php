<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Composite\ConditionalMapping;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Primitive\IntegerCheck;
use Stratadox\Hydration\Mapping\Primitive\IntegerMapping;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\HydrationMapping\KeyedMapping;

final class IntegerValue
{
    public static function inProperty(string $name): KeyedMapping
    {
        return Keyed::mapping($name,
            ConditionalMapping::ensureThat(IntegerCheck::passes(), IntegerMapping::inProperty($name))
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use($key,
            ConditionalMapping::ensureThat(IntegerCheck::passes(), IntegerMapping::inProperty($name))
        ));
    }
}
