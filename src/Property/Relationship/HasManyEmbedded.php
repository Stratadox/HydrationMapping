<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\Hydrates;
use Throwable;

/**
 * Maps a list of scalars to a collection of objects.
 *
 * Takes a list of scalars (eg. an array of strings) and maps it to a collection
 * of single-property objects.
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

    /**
     * Create a new embedded has-many mapping.
     *
     * @param string   $name       The name of the property.
     * @param Hydrates $collection The hydrator for the collection.
     * @param Hydrates $item       The hydrator for the individual items.
     * @param string   $key        The array key to assign to the scalars, used
     *                             by the hydrator for individual items.
     * @return self                The embedded has-many mapping.
     */
    public static function inProperty(
        string $name,
        Hydrates $collection,
        Hydrates $item,
        string $key = 'key'
    ) : self
    {
        return new self($name, $collection, $item, $key);
    }

    public function name() : string
    {
        return $this->name;
    }

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
