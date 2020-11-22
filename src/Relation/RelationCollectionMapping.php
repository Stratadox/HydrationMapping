<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\Mapping;
use Throwable;

final class RelationCollectionMapping implements Mapping
{
    /** @var string */
    private $name;
    /** @var Deserializer */
    private $collection;
    /** @var Deserializer */
    private $item;

    private function __construct(
        string $name,
        Deserializer $collection,
        Deserializer $item
    ) {
        $this->name = $name;
        $this->collection = $collection;
        $this->item = $item;
    }

    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item
    ): Mapping {
        return new self($name, $collection, $item);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(array $data, $owner = null)
    {
        try {
            $objects = $this->itemsFromArray($data);
        } catch (Throwable $exception) {
            throw RelationMappingFailure::encountered($this, $exception);
        }
        try {
            return $this->collection->from($objects);
        } catch (Throwable $exception) {
            throw CollectionMappingFailure::encountered($this, $exception);
        }
    }

    /**
     * @param array[] $data
     * @return object[]
     * @throws DeserializationFailure
     */
    private function itemsFromArray(array $data): array
    {
        $objects = [];
        foreach ($data as $objectData) {
            $objects[] = $this->item->from($objectData);
        }
        return $objects;
    }
}
