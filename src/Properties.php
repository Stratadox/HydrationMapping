<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Stratadox\HydrationMapping\MapsProperties;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\ImmutableCollection\ImmutableCollection;

/**
 * Defines how to map a specific structure of data to an object.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class Properties extends ImmutableCollection implements MapsProperties
{
    public function __construct(MapsProperty ...$properties)
    {
        parent::__construct(...$properties);
    }

    /**
     * Creates a new list of property mappings.
     *
     * @param MapsProperty   ...$properties The property mappings.
     * @return Properties                   The property mapping container.
     */
    public static function map(
        MapsProperty ...$properties
    ) : self
    {
        return new self(...$properties);
    }

    /** @inheritdoc */
    public function current() : MapsProperty
    {
        return parent::current();
    }

    /** @inheritdoc */
    public function offsetGet($offset) : MapsProperty
    {
        return parent::offsetGet($offset);
    }
}
