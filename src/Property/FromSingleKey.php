<?php

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\Hydration\MapsProperty;

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

    protected function my(array $data)
    {
        return $data[$this->key];
    }
}
