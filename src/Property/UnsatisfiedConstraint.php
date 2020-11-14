<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function get_class;
use function is_object;
use function sprintf;

/**
 * Notifies the client code when the input is not accepted by the constraint.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class UnsatisfiedConstraint extends InvalidArgument implements MappingFailure
{
    /**
     * Notifies the client code when the input is not accepted by the constraint.
     *
     * @param Mapping $property The property mapping that denied the input.
     * @param mixed   $value    The input value that was rejected.
     * @return MappingFailure   The exception to throw.
     */
    public static function itIsNotConsideredValid(
        Mapping $property,
        $value
    ): MappingFailure {
        if (is_object($value)) {
            return new self(sprintf(
                'Cannot assign the `%s` to property `%s`: ' .
                'The value did not satisfy the specifications.',
                get_class($value),
                $property->name()
            ));
        }
        return new self(sprintf(
            'Cannot assign `%s` to property `%s`: ' .
            'The value did not satisfy the specifications.',
            $value,
            $property->name()
        ));
    }
}
