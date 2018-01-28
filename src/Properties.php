<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Closure;
use Stratadox\Hydration\MapsProperties;
use Stratadox\Hydration\MapsProperty;
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

    public static function map(
        MapsProperty ...$properties
    ) : self
    {
        return new self(...$properties);
    }

    public function current() : MapsProperty
    {
        return parent::current();
    }

    public function writeData($object, Closure $setter, array $data) : void
    {
        foreach ($this as $property) {
            $setter->call($object, $property->name(), $property->value($data));
        }
    }
}
