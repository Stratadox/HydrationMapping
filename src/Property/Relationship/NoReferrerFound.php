<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use Stratadox\HydrationMapping\MappingFailure;
use function sprintf;

/**
 * Notifies the client code when the referrer was not specified.
 *
 * @author Stratadox
 */
final class NoReferrerFound extends RuntimeException implements MappingFailure
{
    /**
     * Notifies the client code when an item could not be hydrated.
     *
     * @param string $property The property that refers back to nothing.
     * @return MappingFailure  The exception object to throw.
     */
    public static function tryingToHydrateBackReferenceIn(
        string $property
    ): MappingFailure {
        return new self(sprintf(
            'Failed to reference back to the `%s` relationship: no referrer found.',
            $property
        ));
    }
}
