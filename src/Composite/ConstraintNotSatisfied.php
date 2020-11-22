<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Composite;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\Specification\Contract\Satisfiable;
use function get_class;
use function sprintf;
use function var_export;

final class ConstraintNotSatisfied extends RuntimeException implements MappingFailure
{
    /**
     * Notifies the client that the mapping constraint was not satisfied.
     *
     * @param mixed       $result
     * @param Mapping     $mapping
     * @param Satisfiable $constraint
     * @return MappingFailure
     */
    public static function with(
        $result,
        Mapping $mapping,
        Satisfiable $constraint
    ): MappingFailure {
        return new self(sprintf(
            'The %s was refused by the constraint `%s` on property `%s` for the input value %s.',
            get_class($mapping),
            get_class($constraint),
            $mapping->name(),
            var_export($result, true)
        ));
    }
}
