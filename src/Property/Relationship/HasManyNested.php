<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\DifferentKey;
use Stratadox\Hydration\Mapping\Nested;
use Stratadox\Hydration\Mapping\Property\Keyed;
use Stratadox\Hydration\Mapping\Relation\RelationCollectionMapping;
use Stratadox\HydrationMapping\KeyedMapping;

final class HasManyNested
{
    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item
    ): KeyedMapping {
        return Keyed::mapping($name, Nested::inKey(
            $name,
            RelationCollectionMapping::inProperty($name, $collection, $item)
        ));
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        Deserializer $item
    ): KeyedMapping {
        return Keyed::mapping($key, DifferentKey::use(
            $key,
            self::inProperty($name, $collection, $item)
        ));
    }
}
