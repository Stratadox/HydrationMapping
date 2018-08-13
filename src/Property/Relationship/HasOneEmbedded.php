<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializes;
use Stratadox\HydrationMapping\MapsProperty;
use Throwable;

/**
 * Maps an embedded data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasOneEmbedded implements MapsProperty
{
    private $name;
    private $deserialize;

    private function __construct(string $name, Deserializes $deserializer)
    {
        $this->name = $name;
        $this->deserialize = $deserializer;
    }

    /**
     * Creates a new embedded has-one mapping.
     *
     * @param string       $name         The name of the property.
     * @param Deserializes $deserializer The deserializer for the embedded
     *                                   object.
     * @return MapsProperty              The embedded has-one mapping.
     */
    public static function inProperty(
        string $name,
        Deserializes $deserializer
    ): MapsProperty {
        return new self($name, $deserializer);
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
            return $this->deserialize->from($data);
        } catch (Throwable $exception) {
            throw ObjectMappingFailed::tryingToMapItem($this, $exception);
        }
    }
}
