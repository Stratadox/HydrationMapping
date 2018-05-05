<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function gettype;
use function is_scalar;
use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use function trim;

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
     * @param MapsProperty $property
     * @param              $value
     * @return UnsatisfiedConstraint The exception to throw.
     */
    public static function itIsNotConsideredValid(
        MapsProperty $property,
        $value
    ): self {
        return new self(sprintf(
            'Cannot assign `%s` to property `%s`: ' .
            'The value did not satisfy the specifications.',
            $value,
            $property->name()
        ));
    }
}
