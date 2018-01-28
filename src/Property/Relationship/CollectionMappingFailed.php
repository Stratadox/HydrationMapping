<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use ReflectionClass;
use RuntimeException;
use function sprintf;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;
use Throwable;

final class CollectionMappingFailed extends RuntimeException implements UnmappableInput
{
    public static function tryingToMapItem(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s items of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    public static function tryingToMapCollection(
        MapsProperty $mapping,
        Throwable $exception
    ) : self
    {
        return new self(
            sprintf(
                'Failed to map the %s collection of the `%s` property: %s',
                (new ReflectionClass($mapping))->getShortName(),
                $mapping->name(),
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
