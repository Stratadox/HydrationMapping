<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use RuntimeException;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;
use Throwable;

final class ProxyProductionFailed extends RuntimeException implements UnmappableInput
{
    public static function tryingToProduceFor(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
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
