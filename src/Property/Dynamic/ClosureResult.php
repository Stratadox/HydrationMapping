<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Dynamic;

use Closure;
use Stratadox\HydrationMapping\MapsProperty;

/**
 * Maps to the result of a closure that runs against the input data.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class ClosureResult implements MapsProperty
{
    private $property;
    private $function;

    private function __construct(string $property, Closure $function)
    {
        $this->property = $property;
        $this->function = $function;
    }

    /**
     * Creates a new mapping that applies a closure on the input data.
     *
     * @param string $name      The name of both the key and the property.
     * @param Closure $function The function to execute on the data.
     *                          Receives an array of hydration data as parameter.
     * @return self             The closure result mapping.
     */
    public static function inProperty(string $name, Closure $function) : self
    {
        return new self($name, $function);
    }

    /** @inheritdoc */
    public function name() : string
    {
        return $this->property;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        return $this->function->call($this, $data);
    }
}
