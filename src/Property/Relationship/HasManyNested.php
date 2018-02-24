<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\Hydrator\Hydrates;
use Throwable;

/**
 * Maps a nested data structure to a collection in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasManyNested implements ExposesDataKey
{
    private $name;
    private $key;
    private $collection;
    private $item;

    private function __construct(
        string $name,
        string $dataKey,
        Hydrates $collection,
        Hydrates $item
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->collection = $collection;
        $this->item = $item;
    }

    /**
     * Create a new nested has-many mapping.
     *
     * @param string   $name       The name of both the key and the property.
     * @param Hydrates $collection The hydrator for the collection.
     * @param Hydrates $item       The hydrator for the individual items.
     * @return self                The nested has-many mapping.
     */
    public static function inProperty(
        string $name,
        Hydrates $collection,
        Hydrates $item
    ) : self
    {
        return new self($name, $name, $collection, $item);
    }

    /**
     * Create a new nested has-many mapping, using the data from a specific key.
     *
     * @param string   $name       The name of the property.
     * @param string   $key        The name of the key.
     * @param Hydrates $collection The hydrator for the collection.
     * @param Hydrates $item       The hydrator for the individual items.
     * @return self                The nested has-many mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $collection,
        Hydrates $item
    ) : self
    {
        return new self($name, $key, $collection, $item);
    }

    public function name() : string
    {
        return $this->name;
    }

    public function key() : string
    {
        return $this->key;
    }

    public function value(array $data, $owner = null)
    {
        if (!array_key_exists($this->key(), $data)) {
            throw MissingTheKey::inTheInput($data, $this, $this->key());
        }
        try {
            $objects = [];
            foreach ($data[$this->key()] as $objectData) {
                $objects[] = $this->item->fromArray($objectData);
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
