<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;

/**
 * Maps a list of scalars to a collection of objects.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasManyEmbedded implements MapsProperty
{
    private $name;
    private $collection;
    private $item;
    private $key;

    public function __construct(
        string $name,
        Hydrates $collection,
        Hydrates $item,
        string $key
    ) {
        $this->name = $name;
        $this->collection = $collection;
        $this->item = $item;
        $this->key = $key;
    }

    public static function inProperty(
        string $property,
        Hydrates $collection,
        Hydrates $item,
        string $key = 'key'
    ) : MapsProperty
    {
        return new static($property, $collection, $item, $key);
    }

    public function name() : string
    {
        return $this->name;
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        $objects = [];
        try {
            foreach ($data as $value) {
                $objects[] = $this->item->fromArray([$this->key => $value]);
            }
        } catch (UnmappableInput $exception) {
            throw MappingFailed::tryingToMapItem($this, $exception, $this->name);
        }
        try {
            return $this->collection->fromArray($objects);
        } catch (UnmappableInput $exception) {
            throw MappingFailed::tryingToMapCollection($this, $exception, $this->name);
        }
    }
}
