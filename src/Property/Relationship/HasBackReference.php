<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\ObservesHydration;

/**
 * Maps a back-reference in a bidirectional relationship.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasBackReference implements MapsProperty, ObservesHydration
{
    private $name;
    private $referenceTo;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Creates a new mapping that refers back to the "owning" object.
     *
     * @param string $name The name of both the property.
     * @return self        The mapping for the bidirectional relationship.
     */
    public static function inProperty(string $name): self
    {
        return new self($name);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function hydrating(object $theInstance, array $theData): void
    {
        $this->referenceTo = $theInstance;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        if (!isset($this->referenceTo)) {
            throw NoReferrerFound::tryingToHydrateBackReferenceIn($this->name);
        }
        return $this->referenceTo;
    }
}
