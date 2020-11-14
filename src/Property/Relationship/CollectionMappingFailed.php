<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use Throwable;
use function get_class;
use function sprintf;
use function strrchr;
use function substr;

/**
 * Notifies the client code when the collection could not be mapped.
 *
 * @author Stratadox
 */
final class CollectionMappingFailed extends RuntimeException implements MappingFailure
{
    /**
     * Notifies the client code when a collection item could not be hydrated.
     *
     * @param Mapping   $mapping   The item mapping that failed.
     * @param Throwable $exception The exception that was encountered.
     * @return MappingFailure      The collection mapping failure.
     */
    public static function forItem(
        Mapping $mapping,
        Throwable $exception
    ): MappingFailure {
        return new self(
            sprintf(
                'Failed to map the %s items of the `%s` property: %s',
                self::shortNameOfThe($mapping),
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
     * @param Mapping   $mapping   The collection mapping that failed.
     * @param Throwable $exception The exception that was encountered.
     * @return MappingFailure      The collection mapping failure.
     */
    public static function forCollection(
        Mapping $mapping,
        Throwable $exception
    ): MappingFailure {
        return new self(
            sprintf(
                'Failed to map the %s collection of the `%s` property: %s',
                self::shortNameOfThe($mapping),
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
     * @param Mapping $mapping The failing mapping instance.
     * @return string          The unqualified (short) class name of the mapping
     *                         instance.
     */
    private static function shortNameOfThe(Mapping $mapping): string
    {
        return substr(strrchr(get_class($mapping), '\\') ?: '', 1);
    }
}
