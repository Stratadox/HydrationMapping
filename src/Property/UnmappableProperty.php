<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;

/**
 * Exception which is thrown when the input to hydrate is not accepted by the
 * property mapping.
 *
 * @author Stratadox
 * @package Stratadox\Hydrate
 */
final class UnmappableProperty extends InvalidArgument implements UnmappableInput
{
    public static function itMustBeLikeAnInteger(
        MapsProperty $failedToMapTo,
        $value
    ) : UnmappableInput
    {
        return static::inputData($failedToMapTo, 'integer', $value);
    }

    public static function itMustBeInIntegerRange(
        MapsProperty $failedToMapTo,
        $value
    ) : UnmappableInput
    {
        return static::inputData($failedToMapTo, 'integer', $value,
            'The value is out of range.'
        );
    }

    public static function itMustBeNumeric(
        MapsProperty $failedToMapTo,
        $value
    ) : UnmappableInput
    {
        return static::inputData($failedToMapTo, 'float', $value);
    }

    public static function itMustBeConvertibleToBoolean(
        MapsProperty $failedToMapTo,
        $value
    ) : UnmappableInput
    {
        return static::inputData($failedToMapTo, 'boolean', $value);
    }

    protected static function inputData(
        MapsProperty $mapped,
        string $type,
        $input,
        string $message = ''
    ) : UnmappableInput
    {
        if (is_scalar($input)) {
            return new static(sprintf(
                'Cannot assign `%s` to property `%s`: ' .
                'it is not clean for conversion to %s. %s',
                $input, $mapped->name(), $type, $message
            ));
        }
        return new static(sprintf(
            'Cannot assign the %s to property `%s`: ' .
            'it is not clean for conversion to %s. %s',
            gettype($input), $mapped->name(), $type, $message
        ));
    }
}
