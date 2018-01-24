<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\MapsProperty;

/**
 * Maps a back-reference in a bidirectional relationship.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasBackReference implements MapsProperty
{
    private $name;

    protected function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function inProperty(string $name) : MapsProperty
    {
        return new static($name);
    }

    public function name() : string
    {
        return $this->name;
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        return $owner;
    }
}
