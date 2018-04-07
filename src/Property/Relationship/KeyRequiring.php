<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use function assert;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\HydrationMapping\UnmappableInput;

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
     * @param array $data       Input data that must contain our key.
     * @throws UnmappableInput  When the input data does not contain our key.
     */
    private function mustHaveTheKeyInThe(array $data): void
    {
        $mapping = $this;
        assert($mapping instanceof ExposesDataKey);
        if (!isset($data[$mapping->key()])) {
            throw MissingTheKey::inTheInput($data, $mapping, $mapping->key());
        }
    }
}
