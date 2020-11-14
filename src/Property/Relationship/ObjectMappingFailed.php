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
 * Notifies the client code when the object could not be mapped.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class ObjectMappingFailed extends RuntimeException implements MappingFailure
{
    /**
     * Notifies the client code when an object could not be hydrated.
     *
     * @param Mapping   $mapping   The object mapping that failed.
     * @param Throwable $exception The exception that was encountered.
     * @return MappingFailure      The object mapping failure.
     */
    public static function tryingToMapItem(
        Mapping $mapping,
        Throwable $exception
    ): MappingFailure {
        return new self(
            sprintf(
                'Failed to map the %s relation of the `%s` property: %s',
                substr(strrchr(get_class($mapping), '\\'), 1),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
