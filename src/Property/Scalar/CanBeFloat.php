<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function array_key_exists;
use function is_numeric;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Decorates scalar type declaration with a possibly integer property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CanBeFloat implements ExposesDataKey
{
    private $or;

    private function __construct(ExposesDataKey $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new possibly float type wrapper.
     *
     * @param ExposesDataKey $mapping The mapping to decorate.
     * @return self                   The possibly float mapping.
     */
    public static function or(ExposesDataKey $mapping): self
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
        } catch (UnmappableInput $exception) {
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
