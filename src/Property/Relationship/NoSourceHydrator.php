<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use function sprintf;
use Stratadox\Hydration\UnmappableInput;

final class NoSourceHydrator extends RuntimeException implements UnmappableInput
{
    public static function tryingToHydrateBackReferenceIn(string $property) : self
    {
        return new self(sprintf(
            'Failed to reference back to the `%s` relationship: no source defined.',
            $property
        ));
    }
}
