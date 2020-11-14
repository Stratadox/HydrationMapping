<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\KeyedMapping;
use Throwable;

/**
 * Maps a nested data structure to a collection in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasManyNested implements KeyedMapping
{
    use KeyRequiring;

    private $name;
    private $key;
    private $collection;
    private $item;

    private function __construct(
        string $name,
        string $dataKey,
        Deserializer $collection,
        Deserializer $item
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->collection = $collection;
        $this->item = $item;
    }

    /**
     * Creates a new nested has-many mapping.
     *
     * @param string       $name       The name of both the key and the property.
     * @param Deserializer $collection The deserializer for the collection.
     * @param Deserializer $item       The deserializer for the individual items.
     * @return KeyedMapping            The nested has-many mapping.
     */
    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item
    ): KeyedMapping {
        return new self($name, $name, $collection, $item);
    }

    /**
     * Creates a new nested has-many mapping, using the data from a specific
     * key.
     *
     * @param string       $name       The name of the property.
     * @param string       $key        The name of the key.
     * @param Deserializer $collection The deserializer for the collection.
     * @param Deserializer $item       The deserializer for the individual items.
     * @return KeyedMapping            The nested has-many mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializer $collection,
        Deserializer $item
    ): KeyedMapping {
        return new self($name, $key, $collection, $item);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function key(): string
    {
        return $this->key;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $this->mustHaveTheKeyInThe($data);
        try {
            $objects = $this->itemsFromArray($data);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::forItem($this, $exception);
        }
        try {
            return $this->collection->from($objects);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::forCollection($this, $exception);
        }
    }

    /**
     * Hydrates the instances for in a collection.
     *
     * @param array[] $data Map with a list of maps with instance data.
     * @return object[]     The deserialized items for in the collection.
     * @throws DeserializationFailure
     */
    private function itemsFromArray(array $data): array
    {
        $objects = [];
        foreach ($data[$this->key()] as $objectData) {
            $objects[] = $this->item->from($objectData);
        }
        return $objects;
    }
}
