<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\MapsObject;
use Stratadox\ImmutableCollection\ImmutableCollection;

/**
 * Defines how to map a specific structure of data to an object.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class Mapping extends ImmutableCollection implements MapsObject
{
    private $className;

    public function __construct(string $forClass, MapsProperty ...$properties)
    {
        $this->className = $forClass;
        parent::__construct(...$properties);
    }

    public static function ofThe(
        string $class,
        MapsProperty ...$properties
    ) : MapsObject
    {
        return new static($class, ...$properties);
    }

    /** @inheritdoc */
    public function className() : string
    {
        return $this->className;
    }

    /** @inheritdoc */
    public function properties() : array
    {
        return $this->items();
    }
}
