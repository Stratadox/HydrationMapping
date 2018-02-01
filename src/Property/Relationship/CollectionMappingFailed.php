<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use ReflectionClass;
use RuntimeException;
use function sprintf;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use Throwable;

/**
 * Notifies the client code when the collection could not be mapped.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class CollectionMappingFailed extends RuntimeException implements UnmappableInput
{
    /**
     * Notifies the client code when a collection item could not be hydrated.
     *
     * @param MapsProperty $mapping   The item mapping that failed.
     * @param Throwable    $exception The exception that was encountered.
     * @return self                   The collection mapping failure.
     */
    public static function tryingToMapItem(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s items of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    /**
     * Notifies the client code when a collection class could not be hydrated.
     *
     * @param MapsProperty $mapping   The collection mapping that failed.
     * @param Throwable    $exception The exception that was encountered.
     * @return self                   The collection mapping failure.
     */
    public static function tryingToMapCollection(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s collection of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
