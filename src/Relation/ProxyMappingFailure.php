<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use Throwable;
use function sprintf;

final class ProxyMappingFailure extends RuntimeException implements MappingFailure
{
    public static function encountered(
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