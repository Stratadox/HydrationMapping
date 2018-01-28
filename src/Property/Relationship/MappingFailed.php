<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use ReflectionClass;
use RuntimeException;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;
use Throwable;

class MappingFailed extends RuntimeException implements UnmappableInput
{
    public static function tryingToMapItem(
        MapsProperty $mapping,
        Throwable $exception,
        string $property
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s items of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $property,
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    public static function tryingToMapCollection(
        MapsProperty $mapping,
        Throwable $exception,
        string $property
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s collection of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $property,
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
