<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Mapping\Relation\ObservedRelationMapping;

/**
 * Maps a back-reference in a bidirectional relationship.
 *
 * @author Stratadox
 */
final class HasBackReference
{
    public static function inProperty(string $name): ObservedRelationMapping
    {
        return ObservedRelationMapping::inProperty($name);
    }
}
