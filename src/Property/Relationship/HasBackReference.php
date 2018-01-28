<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\MapsProperty;

/**
 * Maps a back-reference in a bidirectional relationship.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasBackReference implements MapsProperty
{
    private $name;
    private $sourceHydrator;

    private function __construct(string $name, ?Hydrates $source)
    {
        $this->name = $name;
        $this->sourceHydrator = $source;
    }

    public static function inProperty(string $name, Hydrates $source = null) : self
    {
        return new self($name, $source);
    }

    /** @inheritdoc */
    public function name() : string
    {
        return $this->name;
    }

    public function setSource(Hydrates $source)
    {
        $this->sourceHydrator = $source;
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        if (!isset($this->sourceHydrator)) {
            throw NoSourceHydrator::tryingToHydrateBackReferenceIn($this->name);
        }
        $instance = $this->sourceHydrator->currentInstance();
        if (!isset($instance)) {
            throw NoReferrerFound::tryingToHydrateBackReferenceIn($this->name);
        }
        return $instance;
    }
}
