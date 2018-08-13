<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function gettype;
use function is_scalar;
use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;
use Throwable;
use function trim;

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
     * @return Throwable                  The exception to throw.
     */
    public static function itMustBeLikeAnInteger(
        MapsProperty $failedToMapTo,
        $value
    ): Throwable {
        return self::inputData($failedToMapTo, 'integer', $value);
    }

    /**
     * Notifies the client code when the input is not in integer range.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return Throwable                  The exception to throw.
     */
    public static function itMustBeInIntegerRange(
        MapsProperty $failedToMapTo,
        $value
    ): Throwable {
        return self::inputData($failedToMapTo, 'integer', $value,
            'The value is out of range.'
        );
    }

    /**
     * Notifies the client code when the input is not formatted as number.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return Throwable                  The exception to throw.
     */
    public static function itMustBeNumeric(
        MapsProperty $failedToMapTo,
        $value
    ): Throwable {
        return self::inputData($failedToMapTo, 'float', $value);
    }

    /**
     * Notifies the client code when the input is not recognised as boolean.
     *
     * @param MapsProperty $failedToMapTo The property that denied the input.
     * @param mixed        $value         The input value that was rejected.
     * @return Throwable                  The exception to throw.
     */
    public static function itMustBeConvertibleToBoolean(
        MapsProperty $failedToMapTo,
        $value
    ): Throwable {
        return self::inputData($failedToMapTo, 'boolean', $value);
    }

    /**
     * Add the alternative type information to the original exception.
     *
     * @param string          $type      The alternative type.
     * @param UnmappableInput $exception The original exception.
     * @return UnmappableInput           The exception to throw.
     */
    public static function addAlternativeTypeInformation(
        string $type,
        UnmappableInput $exception
    ): UnmappableInput {
        return new self(sprintf(
            '%s It could not be mapped to %s either.',
            $exception->getMessage(),
            $type
        ), 0, $exception);
    }

    /**
     * Notifies the client code when the input is not mappable.
     *
     * @param MapsProperty $mapped  The mapping that failed.
     * @param string       $type    The type of data that was expected.
     * @param mixed        $input   The input that could not be mapped.
     * @param string       $message Optional extra message.
     * @return Throwable            The exception to throw.
     */
    private static function inputData(
        MapsProperty $mapped,
        string $type,
        $input,
        string $message = ''
    ): Throwable {
        if (is_scalar($input)) {
            return new self(trim(sprintf(
                'Cannot assign `%s` to property `%s`: ' .
                'it is not clean for conversion to %s. %s',
                $input, $mapped->name(), $type, $message
            )));
        }
        return new self(trim(sprintf(
            'Cannot assign the %s to property `%s`: ' .
            'it is not clean for conversion to %s. %s',
            gettype($input), $mapped->name(), $type, $message
        )));
    }
}
