<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_key_exists;
use function assert;

/**
 * Behaviour to assert that the key is present in the data.
 *
 * @author Stratadox
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
        if (!array_key_exists($mapping->key(), $data)) {
            throw MissingTheKey::inTheInput($data, $mapping, $mapping->key());
        }
    }
}
