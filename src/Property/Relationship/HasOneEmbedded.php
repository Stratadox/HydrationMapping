<?php

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\MapsProperty;

class HasOneEmbedded implements MapsProperty
{
    private $name;
    private $hydrate;

    public function __construct(string $name, Hydrates $hydrate)
    {
        $this->name = $name;
        $this->hydrate = $hydrate;
    }

    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ) : HasOneEmbedded
    {
        return new static($name, $hydrator);
    }

    public function name() : string
    {
        return $this->name;
    }

    /** @return object */
    public function value(array $data, $owner = null)
    {
        return $this->hydrate->fromArray($data);
    }
}