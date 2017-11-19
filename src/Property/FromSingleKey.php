<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\Hydration\MapsProperty;

/**
 * Maps data from a single array position into something else.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
abstract class FromSingleKey implements MapsProperty
{
    private $name;
    private $key;

    public function __construct(string $name, string $dataKey)
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

    protected function my(array $data)
    {
        return $data[$this->key];
    }
}
