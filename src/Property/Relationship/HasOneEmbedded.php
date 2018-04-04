<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\Hydrates;
use Throwable;

/**
 * Maps an embedded data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasOneEmbedded implements MapsProperty
{
    private $name;
    private $hydrate;

    private function __construct(string $name, Hydrates $hydrate)
    {
        $this->name = $name;
        $this->hydrate = $hydrate;
    }

    /**
     * Creates a new embedded has-one mapping.
     *
     * @param string   $name     The name of the property.
     * @param Hydrates $hydrator The hydrator for the embedded object.
     * @return self              The embedded has-one mapping.
     */
    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ): self {
        return new self($name, $hydrator);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->hydrate->fromArray($data);
        } catch (Throwable $exception) {
            throw ObjectMappingFailed::tryingToMapItem($this, $exception);
        }
    }
}
