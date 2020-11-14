<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function array_key_exists;
use function is_numeric;

/**
 * Decorates scalar type declaration with a possibly integer property.
 *
 * @author Stratadox
 */
final class CanBeFloat implements KeyedMapping
{
    /** @var KeyedMapping */
    private $or;

    private function __construct(KeyedMapping $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new possibly float type wrapper.
     *
     * @param KeyedMapping $mapping The mapping to decorate.
     * @return KeyedMapping         The possibly float mapping.
     */
    public static function or(KeyedMapping $mapping): KeyedMapping
    {
        return new self($mapping);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $value = $this->my($data);
        if (is_numeric($value)) {
            return (float) $value;
        }
        try {
            return $this->or->value($data, $owner);
        } catch (MappingFailure $exception) {
            throw UnmappableProperty::addAlternativeTypeInformation(
                'float',
                $exception
            );
        }
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

    /**
     * @throws MappingFailure
     * @return mixed
     */
    private function my(array $data)
    {
        $key = $this->or->key();
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        throw MissingTheKey::inTheInput($data, $this, $key);
    }
}
