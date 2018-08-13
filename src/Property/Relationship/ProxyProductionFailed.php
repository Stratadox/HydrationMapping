<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use function sprintf;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use Throwable;

/**
 * Notifies the client code when the proxy could not be produced.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class ProxyProductionFailed extends RuntimeException implements UnmappableInput
{
    /**
     * Notifies the client code when an item could not be hydrated.
     *
     * @param MapsProperty $mapping   The proxy mapping that failed.
     * @param Throwable    $exception The exception that was encountered.
     * @return Throwable              The proxy production failure.
     */
    public static function tryingToProduceFor(
        MapsProperty $mapping,
        Throwable $exception
    ): Throwable {
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
