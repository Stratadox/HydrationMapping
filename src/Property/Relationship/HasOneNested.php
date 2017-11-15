<?php

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\FromSingleKey;

/**
 * Maps a nested data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrating
 * @author Stratadox
 */
class HasOneNested extends FromSingleKey
{
    private $hydrate;

    public function __construct(string $name, string $dataKey, Hydrates $hydrator)
    {
        $this->hydrate = $hydrator;
        parent::__construct($name, $dataKey);
    }

    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ) : HasOneNested
    {
        return new static($name, $name, $hydrator);
    }

    /** @return object */
    public function value(array $data, $owner = null)
    {
        return $this->hydrate->fromArray($this->my($data));
    }
}
