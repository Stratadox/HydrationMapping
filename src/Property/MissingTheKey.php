<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function get_class;
use function json_encode;
use InvalidArgumentException as InvalidArgument;
use function sprintf;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Notifies the client code when the input key was not found.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class MissingTheKey extends InvalidArgument implements UnmappableInput
{
    /**
     * Notifies the client code about a missing input key.
     *
     * @param array        $data    The data that was provided.
     * @param MapsProperty $mapping The mapping that was expecting a key.
     * @param string       $key     The key that was expected.
     * @return UnmappableInput      The exception to throw.
     */
    public static function inTheInput(
        array $data,
        MapsProperty $mapping,
        string $key
    ): UnmappableInput {
        return new self(sprintf(
            'Missing the key `%s` for property `%s` in the input data: %s; ' .
            'Mapper: %s',
            $key,
            $mapping->name(),
            json_encode($data),
            get_class($mapping)
        ));
    }
}
