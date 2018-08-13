<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Deserializer\Deserializes;
use Stratadox\HydrationMapping\ExposesDataKey;
use Throwable;

/**
 * Maps a nested data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasOneNested implements ExposesDataKey
{
    use KeyRequiring;

    private $name;
    private $key;
    private $deserialize;

    private function __construct(
        string $name,
        string $dataKey,
        Deserializes $deserializer
    ) {
        $this->name = $name;
        $this->key = $dataKey;
        $this->deserialize = $deserializer;
    }

    /**
     * Creates a new nested has-one mapping.
     *
     * @param string       $name         The name of both property and key.
     * @param Deserializes $deserializer The deserializer for the nested object.
     * @return ExposesDataKey            The nested has-one mapping.
     */
    public static function inProperty(
        string $name,
        Deserializes $deserializer
    ): ExposesDataKey {
        return new self($name, $name, $deserializer);
    }

    /**
     * Creates a new nested has-one mapping, using the data from a specific key.
     *
     * @param string       $name         The name of the property.
     * @param string       $key          The name of the key.
     * @param Deserializes $deserializer The deserializer for the nested object.
     * @return ExposesDataKey            The nested has-one mapping.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Deserializes $deserializer
    ): ExposesDataKey {
        return new self($name, $key, $deserializer);
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
            return $this->deserialize->from($data[$this->key()]);
        } catch (Throwable $exception) {
            throw ObjectMappingFailed::tryingToMapItem($this, $exception);
        }
    }
}
