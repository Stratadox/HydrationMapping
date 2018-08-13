<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use function array_key_exists;
use function preg_match;
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
final class CanBeInteger implements ExposesDataKey
{
    private $or;

    private function __construct(ExposesDataKey $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new possibly integer type wrapper.
     *
     * @param ExposesDataKey $mapping The mapping to decorate.
     * @return ExposesDataKey         The possibly integer mapping.
     */
    public static function or(ExposesDataKey $mapping): ExposesDataKey
    {
        return new self($mapping);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $value = $this->my($data);
        if ($this->looksLikeAnInteger((string) $value)) {
            return (int) $value;
        }
        try {
            return $this->or->value($data, $owner);
        } catch (UnmappableInput $exception) {
            throw UnmappableProperty::addAlternativeTypeInformation(
                'integer',
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

    private function looksLikeAnInteger(string $value): bool
    {
        if (!preg_match('/^[-+]?\d+$/', $value)) {
            return false;
        }
        if ($value > (string) PHP_INT_MAX || $value < (string) PHP_INT_MIN) {
            return false;
        }
        return true;
    }
}
