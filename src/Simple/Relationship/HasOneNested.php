<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\Transform\NestingTransform;
use Stratadox\Hydration\Mapping\Relation\RelationMapping;
use Stratadox\HydrationMapping\Mapping;

final class HasOneNested
{
    public static function inProperty(
        string $name,
        Deserializer $deserializer
    ): Mapping {
        return NestingTransform::inKey($name, RelationMapping::inProperty(
            $name,
            $deserializer
        ));
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $deserializer
    ): Mapping {
        return NestingTransform::inKey($key, RelationMapping::inProperty(
            $name,
            $deserializer
        ));
    }
}
