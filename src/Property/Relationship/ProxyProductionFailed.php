<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function sprintf;
use Throwable;

/**
 * Notifies the client code when the proxy could not be produced.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class ProxyProductionFailed extends RuntimeException implements MappingFailure
{
    /**
     * Notifies the client code when an item could not be hydrated.
     *
     * @param Mapping   $mapping   The proxy mapping that failed.
     * @param Throwable $exception The exception that was encountered.
     * @return MappingFailure      The proxy production failure.
     */
    public static function tryingToProduceFor(
        Mapping $mapping,
        Throwable $exception
    ): MappingFailure {
        return new self(
            sprintf(
                'Proxy production for in the `%s` property failed: %s',
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
