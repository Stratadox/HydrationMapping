<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use function get_class as classOfThe;
use RuntimeException;
use function sprintf;
use Stratadox\Hydration\Mapping\Property\Relationship\CollectionMappingFailed as The;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use function strrchr as endOfThe;
use function substr as justThe;
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
                The::shortNameOfThe($mapping),
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
                The::shortNameOfThe($mapping),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    private static function shortNameOfThe(MapsProperty $mapping): string
    {
        return justThe(endOfThe(classOfThe($mapping), '\\'), 1);
    }
}
