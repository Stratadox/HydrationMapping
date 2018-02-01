<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\Hydrates;
use Throwable;

/**
 * Maps a list of scalars to a collection of objects.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasManyEmbedded implements MapsProperty
{
    private $name;
    private $collection;
    private $item;
    private $key;

    private function __construct(
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
    ) : self
    {
        return new self($property, $collection, $item, $key);
    }

    /** @inheritdoc */
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
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::tryingToMapItem($this, $exception);
        }
        try {
            return $this->collection->fromArray($objects);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::tryingToMapCollection($this, $exception);
        }
    }
}
