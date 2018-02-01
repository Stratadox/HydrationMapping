<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function get_class;
use function json_encode;
use function sprintf;
use InvalidArgumentException as InvalidArgument;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;

final class MissingTheKey extends InvalidArgument implements UnmappableInput
{
    public static function inTheInput(array $data, MapsProperty $mapping, string $key) : self
    {
        return new self(sprintf(
            'Missing the key `%s` for property `%s` the input data: %s; Mapper: %s',
            $key,
            $mapping->name(),
            json_encode($data),
            get_class($mapping)
        ));
    }
}
