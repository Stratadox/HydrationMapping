<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use ReflectionClass;
use RuntimeException;
use function sprintf;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use Throwable;

final class ObjectMappingFailed extends RuntimeException implements UnmappableInput
{
    public static function tryingToMapItem(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s relation of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
