<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\HydrationMapping\Mapping;
use Throwable;

/**
 * Sets up a default value for when the input could not be mapped.
 * @todo add logger?
 *
 * @author Stratadox
 */
final class Defaults implements Mapping
{
    private $defaultValue;
    private $mapping;

    private function __construct($defaultValue, Mapping $mapping)
    {
        $this->defaultValue = $defaultValue;
        $this->mapping = $mapping;
    }

    /**
     * Sets up a default value for a property mapping.
     *
     * @param mixed $defaultValue   The value to assign if the original mapping
     *                              failed.
     * @param Mapping $mapping      The original mapping, to try first.
     * @return Mapping              The property mapping with default.
     */
    public static function to(
        $defaultValue,
        Mapping $mapping
    ): Mapping {
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
