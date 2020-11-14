<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function assert;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;

/**
 * Behaviour to assert that the key is present in the data.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
trait KeyRequiring
{
    /**
     * Asserts that our key is present in the input array.
     *
     * @param mixed[] $data   Input data that must contain our key.
     * @throws MappingFailure When the input data does not contain our key.
     */
    private function mustHaveTheKeyInThe(array $data): void
    {
        $mapping = $this;
        assert($mapping instanceof KeyedMapping);
        if (!isset($data[$mapping->key()])) {
            throw MissingTheKey::inTheInput($data, $mapping, $mapping->key());
        }
    }
}
