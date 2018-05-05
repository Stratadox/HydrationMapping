<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function get_class;
use function is_object;
use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Notifies the client code when the input is not accepted by the constraint.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class UnsatisfiedConstraint extends InvalidArgument implements UnmappableInput
{
    /**
     * Notifies the client code when the input is not accepted by the constraint.
     *
     * @param MapsProperty $property The property mapping that denied the input.
     * @param mixed        $value    The input value that was rejected.
     * @return UnsatisfiedConstraint The exception to throw.
     */
    public static function itIsNotConsideredValid(
        MapsProperty $property,
        $value
    ): self {
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
