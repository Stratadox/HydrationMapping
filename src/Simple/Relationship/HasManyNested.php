<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\Transform\KeyTransform;
use Stratadox\Hydration\Mapping\Transform\NestingTransform;
use Stratadox\Hydration\Mapping\Relation\RelationCollectionMapping;
use Stratadox\HydrationMapping\Mapping;

final class HasManyNested
{
    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item
    ): Mapping {
        return NestingTransform::inKey(
            $name,
            RelationCollectionMapping::inProperty($name, $collection, $item)
        );
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        Deserializer $item
    ): Mapping {
        return KeyTransform::use($key, self::inProperty($name, $collection, $item));
    }
}
