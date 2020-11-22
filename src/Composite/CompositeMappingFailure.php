<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Composite;

use RuntimeException;
use Stratadox\HydrationMapping\MappingFailure;
use function lcfirst;
use function sprintf;

final class CompositeMappingFailure extends RuntimeException implements MappingFailure
{
    public static function both(
        MappingFailure $first,
        MappingFailure $second
    ): MappingFailure {
        return new self(sprintf(
            '%s When tried as alternative, %s',
            $first->getMessage(),
            lcfirst($second->getMessage())
        ), 0, $second);
    }
}
