<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function in_array;
use Stratadox\HydrationMapping\ExposesDataKey;
use Stratadox\HydrationMapping\MapsProperty;

/**
 * Decorates @see BooleanValue with custom true/false declarations.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class CustomTruths implements MapsProperty
{
    private $for;
    private $truths;
    private $falsehoods;

    private function __construct(
        ExposesDataKey $mapping,
        array $truths,
        array $falsehoods
    ) {
        $this->for = $mapping;
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
    public static function forThe(
        ExposesDataKey $mapping,
        array $truths,
        array $falsehoods
    ): self {
        return new self($mapping, $truths, $falsehoods);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null): bool
    {
        if (in_array($data[$this->for->key()], $this->truths)) {
            return true;
        }
        if (in_array($data[$this->for->key()], $this->falsehoods)) {
            return false;
        }
        return $this->for->value($data, $owner);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->for->name();
    }
}
