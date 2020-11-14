<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use InvalidArgumentException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function gettype;
use function is_scalar;
use function sprintf;
use function trim;

/**
 * Notifies the client code when the input is not accepted by the property mapping.
 *
 * @author Stratadox
 */
final class UnmappableProperty extends InvalidArgumentException implements MappingFailure
{
    /**
     * Notifies the client code when the input is not formatted as integer.
     *
     * @param Mapping $failedToMapTo The property that denied the input.
     * @param mixed   $value         The input value that was rejected.
     * @return MappingFailure        The exception to throw.
     */
    public static function itMustBeLikeAnInteger(
        Mapping $failedToMapTo,
        $value
    ): MappingFailure {
        return self::inputData($failedToMapTo, 'integer', $value);
    }

    /**
     * Notifies the client code when the input is not in integer range.
     *
     * @param Mapping $failedToMapTo The property that denied the input.
     * @param mixed   $value         The input value that was rejected.
     * @return MappingFailure        The exception to throw.
     */
    public static function itMustBeInIntegerRange(
        Mapping $failedToMapTo,
        $value
    ): MappingFailure {
        return self::inputData($failedToMapTo, 'integer', $value,
            'The value is out of range.'
        );
    }

    /**
     * Notifies the client code when the input is not formatted as number.
     *
     * @param Mapping $failedToMapTo The property that denied the input.
     * @param mixed   $value         The input value that was rejected.
     * @return MappingFailure        The exception to throw.
     */
    public static function itMustBeNumeric(
        Mapping $failedToMapTo,
        $value
    ): MappingFailure {
        return self::inputData($failedToMapTo, 'float', $value);
    }

    /**
     * Notifies the client code when the input is not recognised as boolean.
     *
     * @param Mapping $failedToMapTo The property that denied the input.
     * @param mixed   $value         The input value that was rejected.
     * @return MappingFailure        The exception to throw.
     */
    public static function itMustBeConvertibleToBoolean(
        Mapping $failedToMapTo,
        $value
    ): MappingFailure {
        return self::inputData($failedToMapTo, 'boolean', $value);
    }

    /**
     * Add the alternative type information to the original exception.
     *
     * @param string         $type      The alternative type.
     * @param MappingFailure $exception The original exception.
     * @return MappingFailure           The exception to throw.
     */
    public static function addAlternativeTypeInformation(
        string $type,
        MappingFailure $exception
    ): MappingFailure {
        return new self(sprintf(
            '%s It could not be mapped to %s either.',
            $exception->getMessage(),
            $type
        ), 0, $exception);
    }

    /**
     * Notifies the client code when the input is not mappable.
     *
     * @param Mapping $mapped  The mapping that failed.
     * @param string  $type    The type of data that was expected.
     * @param mixed   $input   The input that could not be mapped.
     * @param string  $message Optional extra message.
     * @return MappingFailure  The exception to throw.
     */
    private static function inputData(
        Mapping $mapped,
        string $type,
        $input,
        string $message = ''
    ): MappingFailure {
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
