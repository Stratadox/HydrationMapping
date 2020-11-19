<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\KeyRequiring;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\KeyedMapping;
use Stratadox\HydrationMapping\MappingFailure;
use function preg_match;

/**
 * Decorates scalar type declaration with a possibly integer property.
 *
 * @author Stratadox
 */
final class CanBeInteger implements KeyedMapping
{
    use KeyRequiring;

    /** @var KeyedMapping */
    private $or;

    private function __construct(KeyedMapping $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new possibly integer type wrapper.
     *
     * @param KeyedMapping $mapping The mapping to decorate.
     * @return KeyedMapping         The possibly integer mapping.
     */
    public static function or(KeyedMapping $mapping): KeyedMapping
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
        } catch (MappingFailure $exception) {
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

    /**
     * @throws MappingFailure
     * @return mixed
     */
    private function my(array $data)
    {
        $key = $this->or->key();
        $this->mustHaveTheKeyInThe($data);
        return $data[$key];
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
