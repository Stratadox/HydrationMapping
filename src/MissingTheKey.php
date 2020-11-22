<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use InvalidArgumentException;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function get_class;
use function json_encode;
use function sprintf;

/**
 * Notifies the client code when the input key was not found.
 *
 * @author Stratadox
 */
final class MissingTheKey extends InvalidArgumentException implements MappingFailure
{
    /**
     * Notifies the client code about a missing input key.
     *
     * @param array    $data    The data that was provided.
     * @param Mapping  $mapping The mapping that was expecting a key.
     * @param string   $key     The key that was expected.
     * @return MappingFailure   The exception to throw.
     */
    public static function inTheInput(
        array $data,
        Mapping $mapping,
        string $key
    ): MappingFailure {
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
