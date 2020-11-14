<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_key_exists;
use function is_null;

/**
 * Decorates scalar type declaration with a nullable property.
 *
 * @author Stratadox
 */
final class CanBeNull implements KeyedMapping
{
    private $or;

    private function __construct(KeyedMapping $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new nullable type wrapper.
     *
     * @param KeyedMapping $mapping The mapping to decorate.
     * @return KeyedMapping         The nullable mapping.
     */
    public static function or(KeyedMapping $mapping): KeyedMapping
    {
        return new self($mapping);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        if (is_null($this->my($data))) {
            return null;
        }
        return $this->or->value($data, $owner);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->or->name();
    }

    /** @inheritdoc */
    public function key(): string
    {
        return $this->or->key();
    }

    /** @throws MappingFailure */
    private function my(array $data)
    {
        $key = $this->or->key();
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        throw MissingTheKey::inTheInput($data, $this, $key);
    }
}
