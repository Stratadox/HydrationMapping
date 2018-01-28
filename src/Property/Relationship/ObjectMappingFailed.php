<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use ReflectionClass;
use RuntimeException;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;
use Throwable;

class ObjectMappingFailed extends RuntimeException implements UnmappableInput
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
