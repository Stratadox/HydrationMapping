<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\FromSingleKey;

class HasManyNested extends FromSingleKey
{
    private $collection;
    private $item;

    public function __construct(
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
    ) : HasManyNested
    {
        return new static($name, $name, $collection, $item);
    }

    /** @return object */
    public function value(array $data, $owner = null)
    {
        $objects = [];
        foreach ($this->my($data) as $objectData) {
            $objects[] = $this->item->fromArray($objectData);
        }
        return $this->collection->fromArray($objects);
    }
}
