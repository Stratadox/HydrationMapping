<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_key_exists;

/**
 * Maps the data from a single key to a scalar object property.
 *
 * @author Stratadox
 */
abstract class ScalarValue implements KeyedMapping
{
    private $name;
    private $key;

    private function __construct(string $name, string $dataKey)
    {
        $this->name = $name;
        $this->key = $dataKey;
    }

    /**
     * Creates a new mapping for the called-upon scalar type object property.
     *
     * @param string $name  The name of both the key and the property.
     * @return KeyedMapping The scalar mapping object.
     */
    public static function inProperty(string $name): KeyedMapping
    {
        return new static($name, $name);
    }

    /**
     * Creates a new mapping for the called-upon scalar type object property,
     * using the data from a specific key.
     *
     * @param string $name  The name of the property.
     * @param string $key   The array key to use.
     * @return KeyedMapping The scalar mapping object.
     */
    public static function inPropertyWithDifferentKey(
        string $name,
        string $key
    ): KeyedMapping {
        return new static($name, $key);
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

    /**
     * Retrieves the data that is relevant for this mapping.
     *
     * @param array $data     The input data.
     * @return mixed          The value for our key in the input array.
     * @throws MappingFailure When the key is missing in the input.
     */
    protected function my(array $data)
    {
        if (!array_key_exists($this->key(), $data)) {
            throw MissingTheKey::inTheInput($data, $this, $this->key());
        }
        return $data[$this->key()];
    }
}
