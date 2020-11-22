<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Simple\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\Relation\RelationMapping;
use Stratadox\HydrationMapping\Mapping;

final class HasOneEmbedded
{
    public static function inProperty(
        string $name,
        Deserializer $deserializer
    ): Mapping {
        return RelationMapping::inProperty($name, $deserializer);
    }
}
