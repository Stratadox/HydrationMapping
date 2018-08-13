<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use function array_key_exists;
use function is_null;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Decorates scalar type declaration with a nullable property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CanBeNull implements ExposesDataKey
{
    private $or;

    private function __construct(ExposesDataKey $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new nullable type wrapper.
     *
     * @param ExposesDataKey $mapping    The mapping to decorate.
     * @return ExposesDataKey            The nullable mapping.
     */
    public static function or(ExposesDataKey $mapping): ExposesDataKey
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

    /** @throws UnmappableInput */
    private function my(array $data)
    {
        $key = $this->or->key();
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        throw MissingTheKey::inTheInput($data, $this, $key);
    }
}
