<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use Throwable;
use function sprintf;

final class RelationMappingFailure extends RuntimeException implements MappingFailure
{
    public static function encountered(
        Mapping $mapping,
        Throwable $exception
    ): MappingFailure {
        return new self(sprintf(
            'Failed to map the relation in the `%s` property: %s',
            $mapping->name(),
            $exception->getMessage()
        ), 0, $exception);
    }
}
