<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Transform;

use RuntimeException;
use Stratadox\HydrationMapping\MappingFailure;
use function sprintf;

final class JsonTransformationFailure extends RuntimeException implements MappingFailure
{
    public static function detected(
        string $error,
        string $key,
        string $name
    ): MappingFailure {
        return new self(sprintf(
            'Error in transforming the json from key `%s` for in the property `%s`: %s',
            $key,
            $name,
            $error
        ));
    }

    public static function cannotBeScalar(
        string $type,
        string $key,
        string $name
    ): self {
        return new self(sprintf(
            'Unexpected %s while transforming the json from key `%s` for in ' .
            'the property `%s`: expecting an array',
            $type,
            $key,
            $name
        ));
    }
}
