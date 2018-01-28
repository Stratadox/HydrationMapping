<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\FromSingleKey;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\UnmappableInput;

/**
 * Maps a nested data structure to a collection in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class HasManyNested extends FromSingleKey
{
    private $collection;
    private $item;

    protected function __construct(
        string $name,
        string $dataKey,
        Hydrates $collection,
        Hydrates $item
    ) {
        parent::__construct($name, $dataKey);
        $this->collection = $collection;
        $this->item = $item;
    }

    public static function inProperty(
        string $name,
        Hydrates $collection,
        Hydrates $item
    ) : MapsProperty
    {
        return new static($name, $name, $collection, $item);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $collection,
        Hydrates $item
    ) : MapsProperty
    {
        return new static($name, $key, $collection, $item);
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        try {
            $objects = [];
            foreach ($this->my($data) as $objectData) {
                $objects[] = $this->item->fromArray($objectData);
            }
        } catch (UnmappableInput $exception) {
            throw MappingFailed::tryingToMapItem($this, $exception, $this->name());
        }
        try {
            return $this->collection->fromArray($objects);
        } catch (UnmappableInput $exception) {
            throw MappingFailed::tryingToMapCollection($this, $exception, $this->name());
        }
    }
}
