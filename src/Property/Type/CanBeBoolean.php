<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use Stratadox\Hydration\Mapping\Property\KeyRequiring;
use Stratadox\HydrationMapping\KeyedMapping;
use function in_array;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\MappingFailure;

/**
 * Decorates @see BooleanValue with custom true/false declarations.
 *
 * @author Stratadox
 */
final class CanBeBoolean implements KeyedMapping
{
    use KeyRequiring;

    /** @var KeyedMapping */
    private $or;
    /** @var mixed[] */
    private $truths;
    /** @var mixed[] */
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
     * Creates a new potentially boolean mapping, decorating a @see BooleanValue.
     *
     * @param KeyedMapping $mapping    The mapping to decorate.
     * @return KeyedMapping            The custom truth boolean mapping.
     */
    public static function or(KeyedMapping $mapping): KeyedMapping
    {
        return new self($mapping, [true, 1, '1'], [false, 0, '0']);
    }

    /**
     * Creates a new potentially mapping with custom truths, decorating a
     * @see BooleanValue.
     *
     * @param KeyedMapping $mapping    The mapping to decorate.
     * @param array        $truths     The values to consider true.
     * @param array        $falsehoods The values to consider false.
     * @return KeyedMapping            The custom truth boolean mapping.
     */
    public static function orCustom(
        KeyedMapping $mapping,
        array $truths,
        array $falsehoods
    ): KeyedMapping {
        return new self($mapping, $truths, $falsehoods);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $this->mustHaveTheKeyInThe($data);
        if (in_array($data[$this->key()], $this->truths, true)) {
            return true;
        }
        if (in_array($data[$this->key()], $this->falsehoods, true)) {
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
