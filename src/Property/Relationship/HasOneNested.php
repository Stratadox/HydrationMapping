<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\Hydrator\Hydrates;
use Throwable;

/**
 * Maps a nested data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasOneNested implements ExposesDataKey
{
    use KeyRequiring;

    private $name;
    private $key;
    private $hydrate;

    private function __construct(string $name, string $dataKey, Hydrates $hydrator)
    {
        $this->name = $name;
        $this->key = $dataKey;
        $this->hydrate = $hydrator;
    }

    /**
     * Creates a new nested has-one mapping.
     *
     * @param string   $name     The name of the property.
     * @param Hydrates $hydrator The hydrator for the nested object.
     * @return self              The nested has-one mapping.
     */
    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ): self {
        return new self($name, $name, $hydrator);
    }

    /**
     * Creates a new nested has-one mapping, using the data from a specific key.
     *
     * @param string   $name     The name of the property.
     * @param string   $key      The name of the key.
     * @param Hydrates $hydrator The hydrator for the nested object.
     * @return self              The nested has-one mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $hydrator
    ): self {
        return new self($name, $key, $hydrator);
    }

    /** @inheritdoc */
    public function name() : string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function key() : string
    {
        return $this->key;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $this->mustHaveTheKeyInThe($data);
        try {
            return $this->hydrate->fromArray($data[$this->key()]);
        } catch (Throwable $exception) {
            throw ObjectMappingFailed::tryingToMapItem($this, $exception);
        }
    }
}
