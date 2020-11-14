<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\Mapping;
use Throwable;

/**
 * Maps a list of scalars to a collection of objects.
 *
 * Takes a list of scalars (eg. an array of strings) and maps it to a collection
 * of single-property objects.
 *
 * @author Stratadox
 */
final class HasManyEmbedded implements Mapping
{
    private $name;
    private $collection;
    private $item;
    private $key;

    private function __construct(
        string $name,
        Deserializer $collection,
        Deserializer $item,
        string $key
    ) {
        $this->name = $name;
        $this->collection = $collection;
        $this->item = $item;
        $this->key = $key;
    }

    /**
     * Creates a new embedded has-many mapping.
     *
     * @param string       $name       The name of the property.
     * @param Deserializer $collection The deserializer for the collection.
     * @param Deserializer $item       The deserializer for the individual items.
     * @param string       $key        The array key to assign to the scalars,
     *                                 used by the deserializer for individual
     *                                 items.
     * @return Mapping                 The embedded has-many mapping.
     */
    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item,
        string $key = 'key'
    ): Mapping {
        return new self($name, $collection, $item, $key);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $objects = [];
        try {
            foreach ($data as $value) {
                $objects[] = $this->item->from([$this->key => $value]);
            }
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::forItem($this, $exception);
        }
        try {
            return $this->collection->from($objects);
        } catch (Throwable $exception) {
            throw CollectionMappingFailed::forCollection($this, $exception);
        }
    }
}
