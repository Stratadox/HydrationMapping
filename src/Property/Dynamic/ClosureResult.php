<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Dynamic;

use Closure;
use Stratadox\Hydration\MapsProperty;

class ClosureResult implements MapsProperty
{
    private $property;
    private $function;

    private function __construct(string $property, Closure $function)
    {
        $this->property = $property;
        $this->function = $function;
    }

    public static function inProperty(string $name, Closure $function) : MapsProperty
    {
        return new static($name, $function);
    }

    public function name() : string
    {
        return $this->property;
    }

    public function value(array $data, $owner = null)
    {
        return $this->function->call($this, $data);
    }
}
