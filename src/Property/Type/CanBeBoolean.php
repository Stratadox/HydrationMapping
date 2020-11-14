<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\HydrationMapping\KeyedMapping;
use function in_array;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\MappingFailure;

/**
 * Decorates @see BooleanValue with custom true/false declarations.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CanBeBoolean implements KeyedMapping
{
    private $or;
    private $truths;
    private $falsehoods;

    private function __construct(
        KeyedMapping $mapping,
        array $truths,
        array $falsehoods
    ) {
        $this->or = $mapping;
        $this->truths = $truths;
        $this->falsehoods = $falsehoods;
    }

    /**
     * Creates a new custom truth mapping, decorating a @see BooleanValue.
     *
     * @param KeyedMapping $mapping    The mapping to decorate.
     * @param array          $truths     The values to consider true.
     * @param array          $falsehoods The values to consider false.
     * @return KeyedMapping            The custom truth boolean mapping.
     */
    public static function or(
        KeyedMapping $mapping,
        array $truths = [true, 1, '1'],
        array $falsehoods = [false, 0, '0']
    ): KeyedMapping {
        return new self($mapping, $truths, $falsehoods);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        if (in_array($data[$this->or->key()], $this->truths, true)) {
            return true;
        }
        if (in_array($data[$this->or->key()], $this->falsehoods, true)) {
            return false;
        }
        try {
            return $this->or->value($data, $owner);
        } catch (MappingFailure $exception) {
            throw UnmappableProperty::addAlternativeTypeInformation(
                'boolean',
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
}
