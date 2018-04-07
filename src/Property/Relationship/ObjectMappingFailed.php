<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use function get_class as classOfThe;
use RuntimeException;
use function sprintf;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use function strrchr as endOfThe;
use function substr as justThe;
use Throwable;

/**
 * Notifies the client code when the object could not be mapped.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class ObjectMappingFailed extends RuntimeException implements UnmappableInput
{
    /**
     * Notifies the client code when an object could not be hydrated.
     *
     * @param MapsProperty $mapping   The object mapping that failed.
     * @param Throwable    $exception The exception that was encountered.
     * @return self                   The object mapping failure.
     */
    public static function tryingToMapItem(
        MapsProperty $mapping,
        Throwable $exception
    ): self {
        return new self(
            sprintf(
                'Failed to map the %s relation of the `%s` property: %s',
                justThe(endOfThe(classOfThe($mapping), '\\'), 1),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
