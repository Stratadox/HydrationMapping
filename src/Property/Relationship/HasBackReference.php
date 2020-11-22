<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Mapping\Relation\ObservedRelationMapping;

/** @deprecated - use ObservedRelationMapping directly instead */
final class HasBackReference
{
    public static function inProperty(string $name): ObservedRelationMapping
    {
        return ObservedRelationMapping::inProperty($name);
    }
}
