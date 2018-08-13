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

    /**
     * Sets up a default value for a property mapping.
     *
     * @param mixed        $defaultValue The value to assign if the original
     *                                   mapping failed.
     * @param MapsProperty $mapping      The original mapping, to try first.
     * @return MapsProperty              The property mapping with default.
     */
    public static function to(
        $defaultValue,
        MapsProperty $mapping
    ): MapsProperty {
        return new self($defaultValue, $mapping);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->mapping->name();
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->mapping->value($data, $owner);
        } catch (Throwable $exception) {
            return $this->defaultValue;
        }
    }
}
