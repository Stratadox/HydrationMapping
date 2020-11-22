<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Hydration\Mapping\Relation\CollectionMappingFailure;
use Stratadox\Hydration\Mapping\Relation\RelationMappingFailure;
use Stratadox\HydrationMapping\Mapping;
use Throwable;

/**
 * @deprecated
 * @codeCoverageIgnore
 */
final class HasManyEmbedded implements Mapping
{
    /** @var string */
    private $name;
    /** @var Deserializer */
    private $collection;
    /** @var Deserializer */
    private $item;
    /** @var string */
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

    public static function inProperty(
        string $name,
        Deserializer $collection,
        Deserializer $item,
        string $key = 'key'
    ): Mapping {
        return new self($name, $collection, $item, $key);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(array $data, $owner = null)
    {
        $objects = [];
        try {
            foreach ($data as $value) {
                $objects[] = $this->item->from([$this->key => $value]);
            }
        } catch (Throwable $exception) {
            throw RelationMappingFailure::encountered($this, $exception);
        }
        try {
            return $this->collection->from($objects);
        } catch (Throwable $exception) {
            throw CollectionMappingFailure::encountered($this, $exception);
        }
    }
}
