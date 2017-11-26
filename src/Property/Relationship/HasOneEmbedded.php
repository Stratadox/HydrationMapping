<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\MapsProperty;

/**
 * Maps an embedded data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasOneEmbedded implements MapsProperty
{
    private $name;
    private $hydrate;

    protected function __construct(string $name, Hydrates $hydrate)
    {
        $this->name = $name;
        $this->hydrate = $hydrate;
    }

    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ) : MapsProperty
    {
        return new static($name, $hydrator);
    }

    public function name() : string
    {
        return $this->name;
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        return $this->hydrate->fromArray($data);
    }
}
