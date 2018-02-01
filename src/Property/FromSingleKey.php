<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use function array_key_exists;
use Stratadox\HydrationMapping\ExposesDataKey;

/**
 * Maps data from a single array position into something else.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
abstract class FromSingleKey implements ExposesDataKey
{
    private $name;
    private $key;

    protected function __construct(string $name, string $dataKey)
    {
        $this->name = $name;
        $this->key = $dataKey;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function key() : string
    {
        return $this->key;
    }

    /**
     * Retrieve the data that is relevant for this mapping.
     *
     * @param array $data The input data.
     * @return mixed      The value for our key in the input array.
     */
    protected function my(array $data)
    {
        if (!array_key_exists($this->key, $data)) {
            throw MissingTheKey::inTheInput($data, $this, $this->key);
        }
        return $data[$this->key];
    }
}
