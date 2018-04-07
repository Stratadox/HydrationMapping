<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function gettype;
use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Notifies the client code when the input is not accepted by the property mapping.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class UnmappableProperty extends InvalidArgument implements UnmappableInput
{
    /**
     * Notifies the client code when the input is not formatted as integer.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return self                       The exception to throw.
     */
    public static function itMustBeLikeAnInteger(
        MapsProperty $failedToMapTo,
        $value
    ): self {
        return self::inputData($failedToMapTo, 'integer', $value);
    }

    /**
     * Notifies the client code when the input is not in integer range.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return self                       The exception to throw.
     */
    public static function itMustBeInIntegerRange(
        MapsProperty $failedToMapTo,
        $value
    ): self {
        return self::inputData($failedToMapTo, 'integer', $value,
            'The value is out of range.'
        );
    }

    /**
     * Notifies the client code when the input is not formatted as number.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return self                       The exception to throw.
     */
    public static function itMustBeNumeric(
        MapsProperty $failedToMapTo,
        $value
    ): self {
        return self::inputData($failedToMapTo, 'float', $value);
    }

    /**
     * Notifies the client code when the input is not recognised as boolean.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return self                       The exception to throw.
     */
    public static function itMustBeConvertibleToBoolean(
        MapsProperty $failedToMapTo,
        $value
    ): self {
        return self::inputData($failedToMapTo, 'boolean', $value);
    }

    /** @inheritdoc */
    private static function inputData(
        MapsProperty $mapped,
        string $type,
        $input,
        string $message = ''
    ): self {
        if (is_scalar($input)) {
            return new self(sprintf(
                'Cannot assign `%s` to property `%s`: ' .
                'it is not clean for conversion to %s. %s',
                $input, $mapped->name(), $type, $message
            ));
        }
        return new self(sprintf(
            'Cannot assign the %s to property `%s`: ' .
            'it is not clean for conversion to %s. %s',
            gettype($input), $mapped->name(), $type, $message
        ));
    }
}
