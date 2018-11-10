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
 * @author  Stratadox
 */
final class CollectionMappingFailed extends RuntimeException implements UnmappableInput
{
    /**
     * Notifies the client code when a collection item could not be hydrated.
     *
     * @param MapsProperty $mapping   The item mapping that failed.
     * @param Throwable    $exception The exception that was encountered.
     * @return UnmappableInput        The collection mapping failure.
     */
    public static function forItem(
        MapsProperty $mapping,
        Throwable $exception
    ): UnmappableInput {
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
     * @return UnmappableInput        The collection mapping failure.
     */
    public static function forCollection(
        MapsProperty $mapping,
        Throwable $exception
    ): UnmappableInput {
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

    /**
     * Retrieves the class name without namespace.
     *
     * @param MapsProperty $mapping The failing mapping instance.
     * @return string               The unqualified (short) class name of the
     *                              mapping instance.
     */
    private static function shortNameOfThe(MapsProperty $mapping): string
    {
        return justThe(endOfThe(classOfThe($mapping), '\\'), 1);
    }
}
