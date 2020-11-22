<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\Nested;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\Hydration\Mapping\Relation\RelationMapping;
use Stratadox\HydrationMapping\KeyedMapping;

final class HasOneNested
{
    public static function inProperty(
        string $name,
        Deserializer $deserializer
    ): KeyedMapping {
        return Keyed::mapping($name,
            Nested::inKey($name, RelationMapping::inProperty($name, $deserializer))
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $deserializer
    ): KeyedMapping {
        return Keyed::mapping($key,
            Nested::inKey($key, RelationMapping::inProperty($name, $deserializer))
        );
    }
}
