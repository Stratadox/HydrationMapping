<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use function sprintf;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Notifies the client code when the referrer was not specified.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoReferrerFound extends RuntimeException implements UnmappableInput
{
    /**
     * Notifies the client code when an item could not be hydrated.
     *
     * @param string $property The property that refers back to nothing.
     * @return self            The exception object to throw.
     */
    public static function tryingToHydrateBackReferenceIn(string $property) : self
    {
        return new self(sprintf(
            'Failed to reference back to the `%s` relationship: no referrer found.',
            $property
        ));
    }
}
