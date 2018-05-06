<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\HydrationMapping\MapsProperty;
use Throwable;

final class Defaults implements MapsProperty
{
    private $defaultValue;
    private $mapping;

    private function __construct($defaultValue, MapsProperty $mapping)
    {
        $this->defaultValue = $defaultValue;
        $this->mapping = $mapping;
    }

    public static function to($defaultValue, MapsProperty $mapping): self
    {
        return new self($defaultValue, $mapping);
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        try {
            return $this->mapping->value($data, $owner);
        } catch (Throwable $exception) {
            return $this->defaultValue;
        }
    }
}
