<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

use function in_array;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * Decorates @see BooleanValue with custom true/false declarations.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CanBeBoolean implements ExposesDataKey
{
    private $or;
    private $truths;
    private $falsehoods;

    private function __construct(
        ExposesDataKey $mapping,
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
     * @param ExposesDataKey $mapping    The mapping to decorate.
     * @param array          $truths     The values to consider true.
     * @param array          $falsehoods The values to consider false.
     * @return self                      The custom truth boolean mapping.
     */
    public static function or(
        ExposesDataKey $mapping,
        array $truths = [true, 1, '1'],
        array $falsehoods = [false, 0, '0']
    ): self {
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
        } catch (UnmappableInput $exception) {
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
