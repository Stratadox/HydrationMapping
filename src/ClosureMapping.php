<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Closure;
use Stratadox\HydrationMapping\Mapping;

final class ClosureMapping implements Mapping
{
    /** @var string */
    private $property;
    /** @var Closure */
    private $function;

    private function __construct(string $property, Closure $function)
    {
        $this->property = $property;
        $this->function = $function;
    }

    public static function inProperty(string $name, Closure $function): Mapping
    {
        return new self($name, $function);
    }

    public function name(): string
    {
        return $this->property;
    }

    public function value(array $data, $owner = null)
    {
        return $this->function->call($this, $data);
    }
}
