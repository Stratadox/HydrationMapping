<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use RuntimeException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_map;
use function implode;
use function var_export;

final class BooleanMappingFailure extends RuntimeException implements MappingFailure
{
    /** @param mixed $value */
    public static function unrecognised(
        $value,
        Mapping $mapping,
        array $trueValues,
        array $falseValues
    ): MappingFailure {
        return new self(sprintf(
            'The conversion to boolean failed for property `%s`: The input ' .
            'value %s is not among the true values (%s) nor the false values (%s)',
            $mapping->name(),
            var_export($value, true),
            self::formatList($trueValues),
            self::formatList($falseValues)
        ));
    }

    private static function formatList(array $values): string
    {
        return implode(', ', array_map(function ($value) {
            return var_export($value, true);
        }, $values));
    }
}
